<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeleteLostItemRequest;
use App\Http\Requests\Api\StoreLostItemRequest;
use App\Models\LostItem;
use App\Models\NfcLog;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SecurityController extends Controller
{
    public function lostItems()
    {
        $items = LostItem::query()
            ->latest('created_at')
            ->get(['id', 'title', 'description', 'image_path', 'created_at'])
            ->map(fn ($item) => [
                'id' => (int) $item->id,
                'title' => (string) $item->title,
                'description' => (string) $item->description,
                'image_path' => $item->image_path ? (string) $item->image_path : null,
                'image_url' => $item->image_path ? $this->buildPublicImageUrl((string) $item->image_path) : null,
                'created_at' => optional($item->created_at)?->toISOString(),
            ])
            ->values();

        return response()->json(['items' => $items]);
    }

    public function storeLostItem(StoreLostItemRequest $request)
    {
        $data = $request->validated();
        $imagePath = null;

        if ($request->hasFile('image')) {
            try {
                $uploadDir = public_path('uploads/lost-items');
                if (! File::isDirectory($uploadDir)) {
                    File::makeDirectory($uploadDir, 0755, true);
                }

                $extension = $request->file('image')->getClientOriginalExtension();
                $fileName = Str::uuid()->toString() . ($extension ? '.' . strtolower($extension) : '');

                $request->file('image')->move($uploadDir, $fileName);
                $imagePath = 'uploads/lost-items/' . $fileName;
            } catch (\Throwable $e) {
                Log::warning('security.lost_items.image_store_failed', [
                    'message' => $e->getMessage(),
                ]);

                return response()->json([
                    'message' => 'Unable to store image',
                    'status_code' => 422,
                    'error_code' => 'LOST_ITEM_IMAGE_STORE_FAILED',
                ], 422);
            }
        }

        $item = LostItem::query()->create([
            'title' => $data['title'],
            'description' => $data['description'],
            'image_path' => $imagePath,
            'created_by_user_id' => $request->user()?->id,
        ]);

        return response()->json([
            'id' => (int) $item->id,
            'title' => (string) $item->title,
            'description' => (string) $item->description,
            'image_path' => $item->image_path,
            'image_url' => $item->image_path ? $this->buildPublicImageUrl((string) $item->image_path) : null,
            'created_at' => optional($item->created_at)?->toISOString(),
        ], 201);
    }

    public function deleteLostItem(DeleteLostItemRequest $request)
    {
        $itemId = (int) $request->validated('id');
        $item = LostItem::query()->find($itemId);

        if (! $item) {
            return response()->json([
                'message' => 'Lost item not found',
                'status_code' => 404,
                'error_code' => 'LOST_ITEM_NOT_FOUND',
            ], 404);
        }

        if (! empty($item->image_path)) {
            $this->deleteImageFile((string) $item->image_path);
        }

        $item->delete();

        return response()->noContent();
    }

    public function entries()
    {
        $logs = NfcLog::query()
            ->with('user:id,name,role')
            ->latest('read_at')
            ->limit(200)
            ->get();

        $missingNfcIds = $logs
            ->filter(fn ($log) => $log->user === null && !empty($log->nfc_id))
            ->pluck('nfc_id')
            ->unique()
            ->values();

        $usersByNfc = User::query()
            ->whereIn('nfc_id', $missingNfcIds)
            ->get(['id', 'name', 'role', 'nfc_id'])
            ->keyBy('nfc_id');

        $items = $logs
            ->map(function ($log) use ($usersByNfc) {
                $resolvedUser = $log->user;

                if (! $resolvedUser && !empty($log->nfc_id)) {
                    $resolvedUser = $usersByNfc->get($log->nfc_id);
                }

                $rawRole = (string) ($resolvedUser->role ?? 'unknown');

                return [
                    'name' => (string) ($resolvedUser->name ?? 'Unknown'),
                    'role' => $this->roleLabel($rawRole),
                    'timestamp' => optional($log->read_at ?? $log->created_at)?->toISOString(),
                ];
            })
            ->values();

        return response()->json(['items' => $items]);
    }

    private function roleLabel(string $role): string
    {
        return match (mb_strtolower(trim($role))) {
            'student' => 'Ученик',
            'teacher' => 'Учител',
            'parent' => 'Родител',
            'security' => 'Охрана',
            'vendor' => 'Стол',
            'admin' => 'Админ',
            default => 'Unknown',
        };
    }

    private function buildPublicImageUrl(string $imagePath): string
    {
        $path = ltrim(trim($imagePath), '/');

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, 'uploads/')) {
            return url('/' . $path);
        }

        if (Str::startsWith($path, 'lost-items/')) {
            $uploadsCandidate = public_path('uploads/' . $path);
            if (File::exists($uploadsCandidate)) {
                return url('/uploads/' . $path);
            }

            return url('/storage/' . $path);
        }

        if (Str::startsWith($path, 'storage/')) {
            return url('/' . $path);
        }

        return url('/uploads/' . $path);
    }

    private function deleteImageFile(string $imagePath): void
    {
        $raw = trim($imagePath);
        if ($raw === '') {
            return;
        }

        if (Str::startsWith($raw, ['http://', 'https://'])) {
            $parsedPath = parse_url($raw, PHP_URL_PATH);
            if (! is_string($parsedPath) || trim($parsedPath) === '') {
                return;
            }
            $raw = $parsedPath;
        }

        $path = ltrim($raw, '/');
        $candidates = [];

        if (Str::startsWith($path, 'uploads/')) {
            $candidates[] = public_path($path);
        } elseif (Str::startsWith($path, 'lost-items/')) {
            $candidates[] = public_path('uploads/' . $path);
            $candidates[] = public_path('storage/' . $path);
            $candidates[] = storage_path('app/public/' . $path);
        } elseif (Str::startsWith($path, 'storage/')) {
            $candidates[] = public_path($path);
            $candidates[] = storage_path('app/public/' . Str::after($path, 'storage/'));
        } else {
            $candidates[] = public_path('uploads/' . $path);
            $candidates[] = public_path($path);
        }

        foreach (array_unique($candidates) as $candidate) {
            try {
                if (File::exists($candidate) && File::isFile($candidate)) {
                    File::delete($candidate);
                }
            } catch (\Throwable $e) {
                Log::warning('security.lost_items.image_delete_failed', [
                    'path' => $candidate,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }
}
