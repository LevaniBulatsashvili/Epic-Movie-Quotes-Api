<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SwaggerController extends Controller
{
    public function login(): JsonResponse
    {
        $tryWith = 'username';

        if (filter_var(request()->username_email, FILTER_VALIDATE_EMAIL)) {
            $tryWith = 'email';
        }

        $authenticated = auth()->attempt(
            [
                $tryWith => request()->username_email,
                'password' => request()->password,
            ]
        );

        if (!$authenticated) {
            return response()->json('wrong email or password', 401);
        }

        $payload = [
            'exp' => Carbon::now()->addMinutes(300)->timestamp,
            'uid' => User::where($tryWith, '=', request()->username_email)->first()->id,
        ];

        $jwt = JWT::encode($payload, config('jwt.secret'), 'HS256');

        return response()->json(['access_token' => $jwt], 200);
    }
}
