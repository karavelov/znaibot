<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\SchoolInfoBlock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SchoolInfoController extends Controller
{
    public function index()
    {
        $items = Cache::remember('api:school_info', now()->addMinutes(10), function () {
            return SchoolInfoBlock::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get(['title', 'description', 'category'])
                ->map(fn ($item) => [
                    'title' => (string) $item->title,
                    'description' => (string) $item->description,
                    'category' => (string) $item->category,
                ])
                ->values();
        });

        return response()->json(['items' => $items]);
    }

    public function clubs()
    {
        $items = Cache::remember('api:clubs', now()->addMinutes(10), function () {
            return Club::query()
                ->where('status', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'about', 'achievements'])
                ->map(function ($club) {
                    $description = trim((string) ($club->about ?? ''));
                    if ($description === '') {
                        $description = trim((string) ($club->achievements ?? ''));
                    }

                    return [
                        'id' => (int) $club->id,
                        'title' => (string) $club->name,
                        'description' => $description,
                        'category' => 'Клубове',
                    ];
                })
                ->values();
        });

        return response()->json(['items' => $items]);
    }

    public function news()
    {
        $items = Cache::remember('api:news', now()->addMinutes(5), function () {
            return DB::table('blogs')
                ->where('status', 1)
                ->orderByDesc('created_at')
                ->get(['id', 'title', 'description', 'slug', 'image', 'created_at'])
                ->map(function ($item) {
                    $image = trim((string) ($item->image ?? ''));
                    $imageUrl = $image !== '' ? url($image) : null;

                    return [
                        'id' => (int) $item->id,
                        'title' => (string) $item->title,
                        'description' => (string) ($item->description ?? ''),
                        'slug' => (string) ($item->slug ?? ''),
                        'image_url' => $imageUrl,
                        'created_at' => (string) ($item->created_at ?? ''),
                    ];
                })
                ->values();
        });

        return response()->json(['items' => $items]);
    }
}
