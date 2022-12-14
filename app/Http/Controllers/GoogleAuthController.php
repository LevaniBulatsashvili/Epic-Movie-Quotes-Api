<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class GoogleAuthController extends Controller
{
    public function redirect(): JsonResponse
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'url' => $url
        ]);
    }

    public function callbackGoogle(): RedirectResponse
    {
        try {
            $google_user = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $google_user->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'username' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                ]);
                Auth::login($user);
            } else {
                Auth::login($user);
            }

            $payload = [
                'exp' => Carbon::now()->addminutes(300)->timestamp,
                'uid' => $user->id,
            ];

            $jwt = JWT::encode($payload, config('jwt.secret'), 'HS256');
            $cookie = cookie('access_token', $jwt, 300, '/', env('FRONTEND_URL'), true, true, false, 'Strict');
            return redirect(env('FULL_FRONTEND_URL'))->withCookie($cookie);
        } catch (\Throwable $th) {
            dd('Something went wrong!' . $th->getMessage());
        }
    }
}
