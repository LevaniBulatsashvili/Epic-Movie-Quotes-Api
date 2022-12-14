<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JwtAuth
{
    public function handle(Request $request, Closure $next): JsonResponse | Closure
    {
        try {
            if (request()->cookie('access_token')) {
                $token = request()->cookie('access_token');
            }

            if (request()->header('Authorization') > 7) {
                $token = substr(request()->header('Authorization'), 7);
            }

            if (!isset($token)) {
                return response()->json(['message' => 'token not present'], 401);
            }

            $decoded = JWT::decode($token, new Key(
                config('jwt.secret'),
                'HS256'
            ));

            if ($decoded->exp > Carbon::now()->timestamp) {
                return $next($request);
            }

            return response()->json(['message' => 'token expired'], 401);
        } catch (Exception $e) {
            return response()->json(['message' => 'token could not be verified'], 401);
        }
    }
}
