<?php

return [
    'enabled' => (bool) env('MQTT_NFC_ENABLED', false),
    'host' => env('MQTT_NFC_HOST', '127.0.0.1'),
    'port' => (int) env('MQTT_NFC_PORT', 1883),
    'username' => env('MQTT_NFC_USERNAME', ''),
    'password' => env('MQTT_NFC_PASSWORD', ''),
    'client_id' => env('MQTT_NFC_CLIENT_ID', 'znaibot-nfc-listener'),
    'topic' => env('MQTT_NFC_TOPIC', 'znaibot/nfc/logs'),
    'qos' => (int) env('MQTT_NFC_QOS', 1),
    'tls' => (bool) env('MQTT_NFC_TLS', false),
    'keep_alive' => (int) env('MQTT_NFC_KEEP_ALIVE', 60),
];
