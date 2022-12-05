<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreForgotPasswordRequest;
use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreRegisterRequest;
use App\Http\Requests\StoreResetPasswordRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    public function register(StoreRegisterRequest $request): JsonResponse
    {
        $attributes = $request->all();
        $attributes['password'] = bcrypt($attributes['password']);

        $user = User::create($attributes);
        event(new Registered($user));

        return response()->json(['message' => 'user created successfully'], 201);
    }

    public function login(StoreLoginRequest $request): JsonResponse
    {
        $remember = $request->remember_me ? true : false;

        $tryWith = 'username';

        if (filter_var($request->username_email, FILTER_VALIDATE_EMAIL)) {
            $tryWith = 'email';
        }

        $token = auth()->attempt([$tryWith => $request->username_email, 'password' => $request->password]);
        if (!$token) {
            return response()->json([
                'username_email' => __('texts.your_provided_credentials_could_not_be_verified'),
                'password' => __('texts.your_provided_credentials_could_not_be_verified')
            ], 404);
        }

        $payload = [
            'exp' => Carbon::now()->addminutes(30)->timestamp,
            'uid' => User::where($tryWith, '=', $request->username_email)->first()->id,
        ];

        $jwt = JWT::encode($payload, config('jwt.secret'), 'HS256');

        $cookie = cookie("access_token", $jwt, 30, '/', env("FRONTEND_URL"), true, true, false, 'Strict');

        return response()->json('success', 200)->withCookie($cookie);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'logout was successful'], 200)->withCookie("access_token", '', 10, '/', env("FRONTEND_URL"), true, true, false, 'Strict');
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function isAuth(): JsonResponse
    {
        return response()->json([
            'message' => 'authenticated successfully',
            'user' => jwtUser()
        ], 200);
    }

    public function forgotPassword(StoreForgotPasswordRequest $request): JsonResponse
    {
        Password::sendResetLink($request->only('email'));

        return Password::RESET_LINK_SENT
                    ? response()->json(['message' => 'password reset link sent successfully'], 200)
                    : response()->json(['message' => 'something went wrong'], 500);
    }

    public function showResetPassword($token): RedirectResponse
    {
        return redirect('http://localhost:5173/new-password/'. $token);
    }

    public function resetPassword(StoreResetPasswordRequest $request): JsonResponse
    {
        Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])
                ->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return response()->json(['message' => 'password reset successful'], 200);
    }

    public function verifyEmail(EmailVerificationRequest $request): Void
    {
        $request->fulfill();
    }

    public function showVerifyEmail(): RedirectResponse
    {
        return redirect('http://localhost:5173/check-email');
    }
}
