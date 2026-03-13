<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestKey = (string) $request->header('X-API-KEY', '');

        if ($requestKey === '') {
            return response()->json([
                'message' => 'Missing API key',
                'status_code' => 401,
                'error_code' => 'API_KEY_MISSING',
            ], 401);
        }

        $plainKeys = array_filter(array_map('trim', explode(',', (string) config('api_security.keys_plain'))));
        $hashedKeys = array_filter(array_map('trim', explode(',', (string) config('api_security.keys_hashed'))));

        $validPlain = in_array($requestKey, $plainKeys, true);
        $validHashed = false;

        foreach ($hashedKeys as $hash) {
            if (hash_equals($hash, hash('sha256', $requestKey))) {
                $validHashed = true;
                break;
            }
        }

        if (! $validPlain && ! $validHashed) {
            return response()->json([
                'message' => 'Invalid API key',
                'status_code' => 401,
                'error_code' => 'API_KEY_INVALID',
            ], 401);
        }

        return $next($request);
    }
}
