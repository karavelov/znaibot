<?php

namespace Database\Seeders;

use App\Models\NfcLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NfcTestSeeder extends Seeder
{
    public function run(): void
    {
        // --- Тестови потребители ---
        $users = [
            [
                'name'     => 'Иван Петров',
                'email'    => 'ivan.petrov@test.nfc',
                'role'     => 'student',
                'nfc_id'   => 'NFC-TEST-001',
                'password' => Hash::make('password'),
            ],
            [
                'name'     => 'Мария Иванова',
                'email'    => 'maria.ivanova@test.nfc',
                'role'     => 'student',
                'nfc_id'   => 'NFC-TEST-002',
                'password' => Hash::make('password'),
            ],
            [
                'name'     => 'Георги Димитров',
                'email'    => 'georgi.dimitrov@test.nfc',
                'role'     => 'student',
                'nfc_id'   => 'NFC-TEST-003',
                'password' => Hash::make('password'),
            ],
            [
                'name'     => 'Анна Стоянова',
                'email'    => 'anna.stoyanova@test.nfc',
                'role'     => 'student',
                'nfc_id'   => 'NFC-TEST-004',
                'password' => Hash::make('password'),
            ],
            [
                'name'     => 'Иванка Тодорова',
                'email'    => 'ivanka.todorova@test.nfc',
                'role'     => 'teacher',
                'nfc_id'   => 'NFC-TEST-005',
                'password' => Hash::make('password'),
            ],
            [
                'name'     => 'Петко Охраната',
                'email'    => 'petko.security@test.nfc',
                'role'     => 'security',
                'nfc_id'   => 'NFC-TEST-006',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(['email' => $data['email']], $data);
        }

        $ivan    = User::where('email', 'ivan.petrov@test.nfc')->first();
        $maria   = User::where('email', 'maria.ivanova@test.nfc')->first();
        $georgi  = User::where('email', 'georgi.dimitrov@test.nfc')->first();
        $anna    = User::where('email', 'anna.stoyanova@test.nfc')->first();
        $ivanka  = User::where('email', 'ivanka.todorova@test.nfc')->first();
        $petko   = User::where('email', 'petko.security@test.nfc')->first();

        $today = now()->toDateString();

        // --- Тестови NFC логове ---
        $logs = [
            // Иван — влезе сутринта, пита Знайбот 2 пъти
            ['user_id' => $ivan->id,   'nfc_id' => 'NFC-TEST-001', 'nfc_reader_id' => 2, 'read_at' => "{$today} 07:45:00"],
            ['user_id' => $ivan->id,   'nfc_id' => 'NFC-TEST-001', 'nfc_reader_id' => 1, 'read_at' => "{$today} 08:10:00"],
            ['user_id' => $ivan->id,   'nfc_id' => 'NFC-TEST-001', 'nfc_reader_id' => 1, 'read_at' => "{$today} 09:30:00"],

            // Мария — влезе, питала Знайбот, излезе и пак влезе
            ['user_id' => $maria->id,  'nfc_id' => 'NFC-TEST-002', 'nfc_reader_id' => 2, 'read_at' => "{$today} 07:50:00"],
            ['user_id' => $maria->id,  'nfc_id' => 'NFC-TEST-002', 'nfc_reader_id' => 1, 'read_at' => "{$today} 08:05:00"],
            ['user_id' => $maria->id,  'nfc_id' => 'NFC-TEST-002', 'nfc_reader_id' => 3, 'read_at' => "{$today} 10:00:00"],
            ['user_id' => $maria->id,  'nfc_id' => 'NFC-TEST-002', 'nfc_reader_id' => 2, 'read_at' => "{$today} 10:15:00"],

            // Георги — влезе и излезе (не е в училище сега)
            ['user_id' => $georgi->id, 'nfc_id' => 'NFC-TEST-003', 'nfc_reader_id' => 2, 'read_at' => "{$today} 08:00:00"],
            ['user_id' => $georgi->id, 'nfc_id' => 'NFC-TEST-003', 'nfc_reader_id' => 3, 'read_at' => "{$today} 11:30:00"],

            // Анна — влезе сутринта
            ['user_id' => $anna->id,   'nfc_id' => 'NFC-TEST-004', 'nfc_reader_id' => 2, 'read_at' => "{$today} 08:20:00"],
            ['user_id' => $anna->id,   'nfc_id' => 'NFC-TEST-004', 'nfc_reader_id' => 1, 'read_at' => "{$today} 09:00:00"],
            ['user_id' => $anna->id,   'nfc_id' => 'NFC-TEST-004', 'nfc_reader_id' => 1, 'read_at' => "{$today} 09:45:00"],
            ['user_id' => $anna->id,   'nfc_id' => 'NFC-TEST-004', 'nfc_reader_id' => 1, 'read_at' => "{$today} 11:00:00"],

            // Иванка (учителка) — влезе
            ['user_id' => $ivanka->id, 'nfc_id' => 'NFC-TEST-005', 'nfc_reader_id' => 2, 'read_at' => "{$today} 07:30:00"],

            // Петко (охрана) — влезе
            ['user_id' => $petko->id,  'nfc_id' => 'NFC-TEST-006', 'nfc_reader_id' => 2, 'read_at' => "{$today} 07:00:00"],

            // Непознат чип (без потребител)
            ['user_id' => null, 'nfc_id' => 'UNKNOWN-CHIP-999', 'nfc_reader_id' => 2, 'read_at' => "{$today} 08:45:00"],
        ];

        foreach ($logs as $log) {
            NfcLog::create($log);
        }

        $this->command->info('✓ NFC тестови данни са добавени: ' . count($users) . ' потребители, ' . count($logs) . ' лога.');
    }
}
