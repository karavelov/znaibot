<?php

return [
    'keys_plain' => env('API_KEYS_PLAIN', ''),
    'keys_hashed' => env('API_KEYS_HASHED', ''),
    'hmac_secret' => env('API_HMAC_SECRET', ''),
    'nonce_ttl_seconds' => (int) env('API_NONCE_TTL_SECONDS', 120),
    'allowed_time_skew_seconds' => (int) env('API_ALLOWED_TIME_SKEW_SECONDS', 120),
    'public_content_token' => env('API_PUBLIC_CONTENT_TOKEN', ''),
    'ai_proxy_endpoint' => env('INTERNAL_LLM_ENDPOINT'),
    'ai_proxy_token' => env('INTERNAL_LLM_TOKEN'),
];
