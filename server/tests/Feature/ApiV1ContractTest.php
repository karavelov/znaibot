<?php

namespace Tests\Feature;

use App\Models\Klas;
use App\Models\ParentMeeting;
use App\Models\SchoolInfoBlock;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiV1ContractTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('api_security.keys_plain', 'test-api-key');
        config()->set('api_security.hmac_secret', 'test-hmac-secret');
        config()->set('api_security.public_content_token', 'public-content-token');
    }

    public function test_login_success_and_failure_returns_json_contract(): void
    {
        User::factory()->create([
            'username' => 'denislav',
            'email' => 'denislav@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'student',
            'status' => 'active',
            'nfc_id' => 'NFC-001',
        ]);

        $payload = [
            'username' => 'denislav',
            'password' => 'secret123',
        ];

        $response = $this->postJson('/api/v1/auth/login', $payload, $this->signedHeaders('POST', '/api/v1/auth/login', $payload));
        $response->assertOk()->assertJsonStructure([
            'id',
            'name',
            'role',
            'linked_student_id',
            'nfc_code',
            'token',
        ]);

        $badPayload = [
            'username' => 'denislav',
            'password' => 'wrong',
        ];

        $this->postJson('/api/v1/auth/login', $badPayload, $this->signedHeaders('POST', '/api/v1/auth/login', $badPayload))
            ->assertStatus(401)
            ->assertJson([
                'status_code' => 401,
                'error_code' => 'LOGIN_INVALID_CREDENTIALS',
            ]);
    }

    public function test_school_info_works_with_public_content_token(): void
    {
        SchoolInfoBlock::query()->create([
            'title' => 'Новина 1',
            'description' => 'Описание',
            'category' => 'Новини',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $headers = $this->signedHeaders('GET', '/api/v1/school/info', [], 'public-content-token');

        $this->getJson('/api/v1/school/info', $headers)
            ->assertOk()
            ->assertJsonStructure([
                'items' => [
                    ['title', 'description', 'category'],
                ],
            ]);
    }

    public function test_teacher_schedule_endpoint_does_not_require_student_id(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
            'status' => 'active',
            'password' => Hash::make('secret123'),
        ]);

        $klas = Klas::query()->create(['title' => '7A']);
        $subject = Subject::query()->create(['name' => 'Математика']);
        $assignment = SubjectTeacher::query()->create([
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'room' => '203',
            'floor' => 2,
            'map_x' => 0.42,
            'map_y' => 0.38,
        ]);

        Schedule::query()->create([
            'klas_id' => $klas->id,
            'semester' => 1,
            'day_of_week' => 1,
            'period' => 1,
            'subject_teacher_id' => $assignment->id,
        ]);

        $token = $teacher->createToken('test')->plainTextToken;

        $this->postJson('/api/v1/schedule', [], $this->signedHeaders('POST', '/api/v1/schedule', [], $token))
            ->assertOk()
            ->assertJsonStructure(['items']);
    }

    public function test_parent_meetings_uses_linked_student_by_default(): void
    {
        $parent = User::factory()->create([
            'role' => 'parent',
            'status' => 'active',
        ]);
        $teacher = User::factory()->create(['role' => 'teacher', 'status' => 'active']);
        $student = User::factory()->create([
            'role' => 'student',
            'status' => 'active',
            'parent_father_id' => $parent->id,
        ]);

        ParentMeeting::query()->create([
            'student_id' => $student->id,
            'parent_id' => $parent->id,
            'teacher_id' => $teacher->id,
            'room' => '203',
            'floor' => 2,
            'meeting_time' => now()->addDay(),
            'note' => 'Test',
            'status' => 'scheduled',
            'created_by_user_id' => $teacher->id,
        ]);

        $token = $parent->createToken('test')->plainTextToken;

        $this->postJson('/api/v1/parents/meetings', [], $this->signedHeaders('POST', '/api/v1/parents/meetings', [], $token))
            ->assertOk()
            ->assertJsonStructure(['items']);

        $payload = ['student_id' => (string) $student->id];
        $this->postJson('/api/v1/parents/meetings', $payload, $this->signedHeaders('POST', '/api/v1/parents/meetings', $payload, $token))
            ->assertOk();
    }

    public function test_security_endpoints_do_not_return_500_for_bad_input(): void
    {
        $security = User::factory()->create([
            'role' => 'security',
            'status' => 'active',
        ]);
        $token = $security->createToken('test')->plainTextToken;

        $this->getJson('/api/v1/security/lost-items', $this->signedHeaders('GET', '/api/v1/security/lost-items', [], $token))
            ->assertOk();

        $payload = ['id' => 999999];
        $this->postJson('/api/v1/security/lost-items/delete', $payload, $this->signedHeaders('POST', '/api/v1/security/lost-items/delete', $payload, $token))
            ->assertStatus(422);

        $this->getJson('/api/v1/security/entries', $this->signedHeaders('GET', '/api/v1/security/entries', [], $token))
            ->assertOk();
    }

    private function signedHeaders(string $method, string $path, array $body = [], ?string $bearerToken = null): array
    {
        $timestamp = time();
        $nonce = (string) Str::uuid();
        $rawBody = empty($body) ? '' : json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $bodyHash = hash('sha256', $rawBody);
        $payload = strtoupper($method).'|'.$path.'|'.$timestamp.'|'.$nonce.'|'.$bodyHash;
        $signature = hash_hmac('sha256', $payload, (string) config('api_security.hmac_secret'));

        $headers = [
            'X-API-KEY' => 'test-api-key',
            'X-Timestamp' => (string) $timestamp,
            'X-Nonce' => $nonce,
            'X-Signature' => $signature,
            'Accept' => 'application/json',
        ];

        if ($bearerToken) {
            $headers['Authorization'] = 'Bearer '.$bearerToken;
        }

        return $headers;
    }
}
