<?php

namespace App\Console\Commands;

use App\Services\NfcLogIngestService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class NfcMqttListenCommand extends Command
{
    protected $signature = 'nfc:mqtt-listen {--once : Process a single message and exit}';
    protected $description = 'Listen for NFC logs, store them, and send OPEN/DENIED back to ESP8266';

    public function handle(NfcLogIngestService $ingestService): int
    {
        if (!(bool) config('mqtt_nfc.enabled', false)) {
            $this->error('MQTT NFC listener is disabled.');
            return self::FAILURE;
        }

        $host = (string) config('mqtt_nfc.host', '127.0.0.1');
        $port = (int) config('mqtt_nfc.port', 1883);
        $clientId = (string) config('mqtt_nfc.client_id', 'znaibot-nfc-listener');
        $topic = (string) config('mqtt_nfc.topic', 'znaibot/nfc/logs');
        $responseTopic = 'znaibot/nfc/response';
        $qos = (int) config('mqtt_nfc.qos', 1);

        $connectionSettings = (new ConnectionSettings())
            ->setUsername((string) config('mqtt_nfc.username', ''))
            ->setPassword((string) config('mqtt_nfc.password', ''))
            ->setKeepAliveInterval((int) config('mqtt_nfc.keep_alive', 60))
            ->setUseTls((bool) config('mqtt_nfc.tls', false));

        $mqtt = new MqttClient($host, $port, $clientId, MqttClient::MQTT_3_1_1);

        try {
            $mqtt->connect($connectionSettings, true);
            $this->info("Connected to MQTT broker {$host}:{$port}");

            $processedOne = false;

            $mqtt->subscribe($topic, function (string $topicName, string $message) use ($ingestService, $mqtt, $responseTopic, &$processedOne): void {
                $payload = json_decode($message, true);

                if (!is_array($payload) || empty($payload['nfc_id'])) {
                    Log::warning('mqtt.nfc.invalid_payload', ['message' => $message]);
                    $mqtt->publish($responseTopic, 'DENIED', 1);
                    return;
                }

                try {
                    // Опитваме да запишем лога и проверим потребителя
                    $result = $ingestService->store($payload);

                    if ($result) {
                        // Потребителят е намерен и логнат успешно
                        $mqtt->publish($responseTopic, 'OPEN', 1);
                        $processedOne = true;
                        Log::info('mqtt.nfc.access_granted', ['nfc_id' => $payload['nfc_id']]);
                    } else {
                        // Потребителят не съществува или няма права
                        $mqtt->publish($responseTopic, 'DENIED', 1);
                        Log::warning('mqtt.nfc.access_denied', ['nfc_id' => $payload['nfc_id']]);
                    }
                } catch (\Throwable $e) {
                    $mqtt->publish($responseTopic, 'DENIED', 1);
                    Log::error('mqtt.nfc.store_failed', ['message' => $e->getMessage()]);
                }
            }, $qos);

            $this->info("Subscribed to topic: {$topic}");

            if ((bool) $this->option('once')) {
                while (!$processedOne) {
                    $mqtt->loop(true, true, 1);
                }
            } else {
                $mqtt->loop(true);
            }

            $mqtt->disconnect();
            return self::SUCCESS;

        } catch (\Throwable $e) {
            Log::error('mqtt.nfc.listen_failed', ['message' => $e->getMessage()]);
            $this->error('MQTT listener failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}