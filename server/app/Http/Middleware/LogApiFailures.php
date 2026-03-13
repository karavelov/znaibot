<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiFailures
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->is('api/*')) {
            return $response;
        }

        if ($response->getStatusCode() < 400) {
            return $response;
        }

        $input = $request->except([
            'password',
            'password_confirmation',
            'token',
            'api_key',
            'signature',
            'X-API-KEY',
            'X-Signature',
        ]);

        $headers = [
            'x-api-key' => $request->header('X-API-KEY') ? '***' : null,
            'x-timestamp' => $request->header('X-Timestamp'),
            'x-nonce' => $request->header('X-Nonce'),
            'x-signature' => $request->header('X-Signature') ? '***' : null,
            'authorization' => $request->bearerToken() ? 'Bearer ***' : null,
        ];

        Log::warning('api.request_failed', [
            'method' => $request->method(),
            'path' => '/'.$request->path(),
            'status_code' => $response->getStatusCode(),
            'user_id' => optional($request->user())->id,
            'ip' => $request->ip(),
            'headers' => array_filter($headers, fn ($value) => $value !== null),
            'input' => $input,
            'response' => $this->extractResponsePayload($response),
        ]);

        return $response;
    }

    private function extractResponsePayload(Response $response): array|string|null
    {
        $content = $response->getContent();
        if (! is_string($content) || $content === '') {
            return null;
        }

        $decoded = json_decode($content, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : substr($content, 0, 1000);
    }
}
