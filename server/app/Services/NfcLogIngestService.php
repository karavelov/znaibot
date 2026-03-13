<?php

namespace App\Services;

use App\Models\NfcLog;
use App\Models\NfcReader;
use App\Models\User;
use Carbon\Carbon;

class NfcLogIngestService
{
    public function store(array $payload): array
    {
        $nfcId = trim((string) ($payload['nfc_id'] ?? ''));
        $direction = $this->normalizeDirection((string) ($payload['direction'] ?? 'in'));
        $readerType = $direction === 'in' ? 'door_in' : 'door_out';

        $reader = null;

        if (! empty($payload['nfc_reader_id'])) {
            $reader = NfcReader::query()->find((int) $payload['nfc_reader_id']);
        }

        if (! $reader) {
            $readerTitle = ! empty($payload['reader_title'])
                ? trim((string) $payload['reader_title'])
                : ($direction === 'in' ? 'NFC Вход' : 'NFC Изход');

            $reader = NfcReader::query()->firstOrCreate(
                ['title' => $readerTitle, 'type' => $readerType],
                ['title' => $readerTitle, 'type' => $readerType]
            );
        }

        $user = User::query()->where('nfc_id', $nfcId)->first();
        $readAt = ! empty($payload['read_at']) ? Carbon::parse((string) $payload['read_at']) : now();

        $log = NfcLog::query()->create([
            'user_id' => $user?->id,
            'nfc_id' => $nfcId,
            'nfc_reader_id' => $reader->id,
            'read_at' => $readAt,
        ]);

        return [
            'status' => 'ok',
            'id' => (int) $log->id,
            'direction' => $direction,
            'timestamp' => optional($log->read_at)?->toISOString(),
            'reader' => [
                'id' => (int) $reader->id,
                'title' => (string) $reader->title,
                'type' => (string) $reader->type,
            ],
            'user' => $user ? [
                'id' => (int) $user->id,
                'name' => (string) $user->name,
                'role' => (string) $user->role,
            ] : null,
            'unknown' => $user === null,
        ];
    }

    public function normalizeDirection(string $direction): string
    {
        $value = mb_strtolower(trim($direction));

        if (in_array($value, ['in', 'entry', 'door_in', 'вход'], true)) {
            return 'in';
        }

        return 'out';
    }
}
