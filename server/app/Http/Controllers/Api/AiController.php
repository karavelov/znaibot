<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AiChatRequest;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function chat(AiChatRequest $request)
    {
        $endpoint = (string) config('api_security.ai_proxy_endpoint');
        $token = (string) config('api_security.ai_proxy_token');

        if ($endpoint === '') {
            return response()->json([
                'message' => 'AI service unavailable',
                'status_code' => 503,
            ], 503);
        }

        $client = Http::timeout(25);
        if ($token !== '') {
            $client = $client->withToken($token);
        }

        $upstream = $client->post($endpoint, [
            'prompt' => $request->validated('prompt'),
        ]);

        if (! $upstream->successful()) {
            return response()->json([
                'message' => 'AI service unavailable',
                'status_code' => 502,
            ], 502);
        }

        return response()->json([
            'response' => (string) ($upstream->json('response') ?? $upstream->body()),
        ]);
    }
}
