<?php

namespace Database\Seeders;

use App\Models\Klas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

/**
 * ТЕСТОВИ ДАННИ — изтрий след визуализация!
 * php8.1 artisan db:seed --class=BirthdayTestSeeder
 *
 * За изтриване на записите и снимките:
 *   php8.1 artisan tinker
 *   App\Models\User::where('email','like','%@birthday-test.dev')->each(fn($u) => (File::exists(public_path($u->image)) && File::delete(public_path($u->image))) || $u->delete());
 *
 * Или само SQL: DELETE FROM users WHERE email LIKE '%@birthday-test.dev';
 */
class BirthdayTestSeeder extends Seeder
{
    // pravatar.cc връща реални случайни лица (консистентни по seed)
    // Мъже: 1-70 (нечетни по-мъжествени), Жени: 1-70
    private array $maleSeeds   = [10, 20, 30, 40, 50, 60];
    private array $femaleSeeds = [11, 21, 31, 41];

    public function run(): void
    {
        $today    = now()->format('m-d');
        $tomorrow = now()->addDay()->format('m-d');

        $klas7a  = Klas::firstOrCreate(['title' => '7А']);
        $klas10b = Klas::firstOrCreate(['title' => '10Б']);
        $klas5v  = Klas::firstOrCreate(['title' => '5В']);

        $users = [
            // ── ДНЕС ──────────────────────────────────────────────
            [
                'name'        => 'Иван Петров (Учител)',
                'email'       => 'ivan.petrov@birthday-test.dev',
                'role'        => 'teacher',
                'gender'      => 'male',
                'birth_date'  => "1978-{$today}",
                'klas_id'     => null,
                'avatar_seed' => $this->maleSeeds[0],
            ],
            [
                'name'        => 'Мария Тодорова (Учителка)',
                'email'       => 'maria.todorova@birthday-test.dev',
                'role'        => 'teacher',
                'gender'      => 'female',
                'birth_date'  => "1985-{$today}",
                'klas_id'     => null,
                'avatar_seed' => $this->femaleSeeds[0],
            ],
            [
                'name'        => 'Георги Стоянов (7А)',
                'email'       => 'georgi.stoyanov@birthday-test.dev',
                'role'        => 'student',
                'gender'      => 'male',
                'birth_date'  => "2012-{$today}",
                'klas_id'     => $klas7a->id,
                'avatar_seed' => $this->maleSeeds[1],
            ],
            [
                'name'        => 'Елена Василева (7А)',
                'email'       => 'elena.vasileva@birthday-test.dev',
                'role'        => 'student',
                'gender'      => 'female',
                'birth_date'  => "2012-{$today}",
                'klas_id'     => $klas7a->id,
                'avatar_seed' => $this->femaleSeeds[1],
            ],
            [
                'name'        => 'Никола Димитров (10Б)',
                'email'       => 'nikola.dimitrov@birthday-test.dev',
                'role'        => 'student',
                'gender'      => 'male',
                'birth_date'  => "2009-{$today}",
                'klas_id'     => $klas10b->id,
                'avatar_seed' => $this->maleSeeds[2],
            ],
            [
                'name'        => 'Петър Колев (Родител)',
                'email'       => 'petar.kolev@birthday-test.dev',
                'role'        => 'parent',
                'gender'      => 'male',
                'birth_date'  => "1975-{$today}",
                'klas_id'     => null,
                'avatar_seed' => $this->maleSeeds[3],
            ],

            // ── УТРЕ ──────────────────────────────────────────────
            [
                'name'        => 'Стефан Иванов (Учител)',
                'email'       => 'stefan.ivanov@birthday-test.dev',
                'role'        => 'teacher',
                'gender'      => 'male',
                'birth_date'  => "1980-{$tomorrow}",
                'klas_id'     => null,
                'avatar_seed' => $this->maleSeeds[4],
            ],
            [
                'name'        => 'Анна Георгиева (5В)',
                'email'       => 'anna.georgieva@birthday-test.dev',
                'role'        => 'student',
                'gender'      => 'female',
                'birth_date'  => "2014-{$tomorrow}",
                'klas_id'     => $klas5v->id,
                'avatar_seed' => $this->femaleSeeds[2],
            ],
            [
                'name'        => 'Борис Николов (10Б)',
                'email'       => 'boris.nikolov@birthday-test.dev',
                'role'        => 'student',
                'gender'      => 'male',
                'birth_date'  => "2009-{$tomorrow}",
                'klas_id'     => $klas10b->id,
                'avatar_seed' => $this->maleSeeds[5],
            ],
            [
                'name'        => 'Снежана Христова (Родител)',
                'email'       => 'snezhana.hristova@birthday-test.dev',
                'role'        => 'parent',
                'gender'      => 'female',
                'birth_date'  => "1979-{$tomorrow}",
                'klas_id'     => null,
                'avatar_seed' => $this->femaleSeeds[3],
            ],
        ];

        // Папка за снимките — същия формат като ImageUploadTrait
        $year  = date('Y');
        $month = date('m');
        $dir   = "uploads/{$year}/{$month}/t1";
        $fullDir = public_path($dir);

        if (!File::isDirectory($fullDir)) {
            File::makeDirectory($fullDir, 0777, true);
        }

        foreach ($users as $data) {
            $seed       = $data['avatar_seed'];
            $avatarSeed = $data['gender'] === 'female' ? "women/{$seed}" : "men/{$seed}";
            $url        = "https://randomuser.me/api/portraits/{$avatarSeed}.jpg";
            $filename   = "birthday_test_{$seed}_{$data['gender']}.jpg";
            $localPath  = "{$dir}/{$filename}";
            $fullPath   = public_path($localPath);

            // Изтегли снимката само ако не съществува вече
            if (!File::exists($fullPath)) {
                $this->command->line("  ⬇  Изтеглям снимка: {$url}");
                try {
                    $response = Http::timeout(10)->get($url);
                    if ($response->successful()) {
                        File::put($fullPath, $response->body());
                        $this->command->info("     ✔ Запазена: {$localPath}");
                    } else {
                        $this->command->warn("     ✗ Неуспешно изтегляне (HTTP {$response->status()}) — ще се ползва placeholder.");
                        $localPath = null;
                    }
                } catch (\Exception $e) {
                    $this->command->warn("     ✗ Грешка: {$e->getMessage()} — ще се ползва placeholder.");
                    $localPath = null;
                }
            } else {
                $this->command->line("  ✔  Снимката вече съществува: {$localPath}");
            }

            unset($data['avatar_seed']);

            User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => Hash::make('password'),
                    'status'   => 'active',
                    'image'    => $localPath,
                ])
            );
        }

        $this->command->newLine();
        $this->command->info('✅ Създадени ' . count($users) . ' тестови рожденика със снимки.');
        $this->command->warn('⚠️  За изтриване: DELETE FROM users WHERE email LIKE \'%@birthday-test.dev\';');
        $this->command->warn('    + изтрий файловете от public/' . $dir . '/birthday_test_*');
    }
}
