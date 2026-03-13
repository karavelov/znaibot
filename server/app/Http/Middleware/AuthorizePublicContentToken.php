<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthorizePublicContentToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = trim((string) $request->bearerToken());

        if ($token === '') {
            return response()->json([
                'message' => 'Missing bearer token',
                'status_code' => 401,
                'error_code' => 'CONTENT_TOKEN_MISSING',
            ], 401);
        }

        $publicToken = (string) config('api_security.public_content_token', '');
        if ($publicToken !== '' && hash_equals($publicToken, $token)) {
            return $next($request);
        }

        $accessToken = PersonalAccessToken::findToken($token);
        if ($accessToken && $accessToken->tokenable) {
            Auth::setUser($accessToken->tokenable);

            return $next($request);
        }

        return response()->json([
            'message' => 'Invalid bearer token',
            'status_code' => 401,
            'error_code' => 'CONTENT_TOKEN_INVALID',
        ], 401);
    }
}
