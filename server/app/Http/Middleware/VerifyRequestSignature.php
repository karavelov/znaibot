<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class VerifyRequestSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $timestamp = (int) $request->header('X-Timestamp', 0);
        $nonce = (string) $request->header('X-Nonce', '');
        $signature = (string) $request->header('X-Signature', '');
        $secret = (string) config('api_security.hmac_secret');
        $ttl = (int) config('api_security.nonce_ttl_seconds', 120);
        $allowedSkew = (int) config('api_security.allowed_time_skew_seconds', 120);

        if ($timestamp <= 0 || $nonce === '' || $signature === '' || $secret === '') {
            return response()->json([
                'message' => 'Missing signature headers or server signature secret is not configured',
                'status_code' => 401,
                'error_code' => 'SIGNATURE_HEADERS_MISSING',
            ], 401);
        }

        if (abs(time() - $timestamp) > $allowedSkew) {
            return response()->json([
                'message' => 'Timestamp outside allowed window',
                'status_code' => 401,
                'error_code' => 'TIMESTAMP_INVALID',
            ], 401);
        }

        if (! preg_match('/^[0-9a-fA-F-]{36}$/', $nonce)) {
            return response()->json([
                'message' => 'Invalid nonce format',
                'status_code' => 401,
                'error_code' => 'NONCE_INVALID',
            ], 401);
        }

        $cacheKey = 'api_nonce:'.$nonce;
        if (! Cache::add($cacheKey, true, now()->addSeconds($ttl))) {
            return response()->json([
                'message' => 'Nonce already used',
                'status_code' => 401,
                'error_code' => 'NONCE_REPLAY',
            ], 401);
        }

        $payload = implode('|', [
            strtoupper($request->method()),
            '/'.$request->path(),
            $timestamp,
            $nonce,
            hash('sha256', (string) $request->getContent()),
        ]);

        $expected = hash_hmac('sha256', $payload, $secret);
        if (! hash_equals($expected, $signature)) {
            Cache::forget($cacheKey);

            return response()->json([
                'message' => 'Invalid request signature',
                'status_code' => 401,
                'error_code' => 'SIGNATURE_INVALID',
            ], 401);
        }

        return $next($request);
    }
}
