<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $identifier = trim((string) $data['username']);

        $user = User::query()
            ->where(function ($query) use ($identifier) {
                $query->whereRaw('LOWER(username) = ?', [mb_strtolower($identifier)])
                    ->orWhereRaw('LOWER(email) = ?', [mb_strtolower($identifier)]);
            })
            ->first();

        if (! $user || ! $this->passwordMatches($data['password'], (string) $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'status_code' => 401,
                'error_code' => 'LOGIN_INVALID_CREDENTIALS',
            ], 401);
        }

        if (! $this->isBcryptHash((string) $user->password)) {
            $user->forceFill([
                'password' => Hash::make($data['password']),
            ])->save();
        }

        $linkedStudentId = null;
        if ($user->role === 'parent') {
            $linkedStudentId = User::query()
                ->where('role', 'student')
                ->where(function ($query) use ($user) {
                    $query->where('parent_father_id', $user->id)
                        ->orWhere('parent_mother_id', $user->id);
                })
                ->value('id');
        }

        $token = $user->createToken('mobile-api')->plainTextToken;
        $className = $this->resolveUserClassName($user, $linkedStudentId);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'class_name' => $className,
            'linked_student_id' => $linkedStudentId,
            'nfc_code' => $user->nfc_id,
            'token' => $token,
        ]);
    }

    public function me()
    {
        $user = request()->user();

        $linkedStudentId = null;
        if ($user->role === 'parent') {
            $linkedStudentId = User::query()
                ->where('role', 'student')
                ->where(function ($query) use ($user) {
                    $query->where('parent_father_id', $user->id)
                        ->orWhere('parent_mother_id', $user->id);
                })
                ->value('id');
        }

        $className = $this->resolveUserClassName($user, $linkedStudentId);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'class_name' => $className,
            'linked_student_id' => $linkedStudentId,
            'nfc_code' => $user->nfc_id,
            'role' => $user->role,
        ]);
    }

    private function resolveUserClassName(User $user, ?int $linkedStudentId): ?string
    {
        if ($user->role === 'student') {
            $student = User::query()
                ->with('klas:id,title')
                ->where('id', $user->id)
                ->first();

            return $student?->klas?->title;
        }

        if ($user->role === 'parent' && $linkedStudentId) {
            $student = User::query()
                ->with('klas:id,title')
                ->where('id', $linkedStudentId)
                ->where('role', 'student')
                ->first();

            return $student?->klas?->title;
        }

        return null;
    }

    public function logout()
    {
        $token = request()->user()?->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        return response()->noContent();
    }

    private function passwordMatches(string $plainPassword, string $storedPassword): bool
    {
        $storedPassword = trim($storedPassword);

        if ($storedPassword === '') {
            return false;
        }

        if ($this->isBcryptHash($storedPassword)) {
            return password_verify($plainPassword, $storedPassword);
        }

        if (str_starts_with($storedPassword, '$argon2')) {
            return password_verify($plainPassword, $storedPassword);
        }

        if (str_starts_with($storedPassword, '$')) {
            return password_verify($plainPassword, $storedPassword);
        }

        if ($this->looksLikeHexHash($storedPassword, 32)) {
            return hash_equals(strtolower($storedPassword), md5($plainPassword));
        }

        if ($this->looksLikeHexHash($storedPassword, 40)) {
            return hash_equals(strtolower($storedPassword), sha1($plainPassword));
        }

        if ($this->looksLikeHexHash($storedPassword, 64)) {
            return hash_equals(strtolower($storedPassword), hash('sha256', $plainPassword));
        }

        return hash_equals($storedPassword, $plainPassword);
    }

    private function isBcryptHash(string $value): bool
    {
        return str_starts_with($value, '$2y$')
            || str_starts_with($value, '$2b$')
            || str_starts_with($value, '$2a$');
    }

    private function looksLikeHexHash(string $value, int $length): bool
    {
        return strlen($value) === $length && ctype_xdigit($value);
    }
}
