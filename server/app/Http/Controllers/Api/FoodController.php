<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FoodAllergenSyncRequest;
use App\Models\Allergen;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FoodController extends Controller
{
    public function allergens()
    {
        $items = Cache::remember('api:allergens', now()->addMinutes(30), function () {
            return Allergen::query()
                ->orderBy('name')
                ->get(['id', 'name', 'description', 'color'])
                ->map(fn ($allergen) => [
                    'id' => $allergen->id,
                    'name' => $allergen->name,
                    'description' => $allergen->description,
                    'color' => $allergen->color,
                ])
                ->values();
        });

        return response()->json(['items' => $items]);
    }

    public function studentsAllergens()
    {
        $items = User::query()
            ->with(['klas:id,title', 'allergens:id,name,description,color'])
            ->where('role', 'student')
            ->orderBy('name')
            ->get(['id', 'name', 'klas_id'])
            ->map(function ($student) {
                $notesMap = [];
                foreach ($student->allergens as $allergen) {
                    $notesMap[(string) $allergen->id] = $allergen->pivot->notes;
                }

                return [
                    'student_id' => (string) $student->id,
                    'student_name' => $student->name,
                    'class_name' => optional($student->klas)->title ?? '',
                    'allergens' => $student->allergens->map(fn ($allergen) => [
                        'id' => $allergen->id,
                        'name' => $allergen->name,
                        'description' => $allergen->description,
                        'color' => $allergen->color,
                    ])->values(),
                    'notes_by_allergen_id' => (object) $notesMap,
                ];
            })
            ->values();

        return response()->json(['items' => $items]);
    }

    public function syncStudentAllergens(FoodAllergenSyncRequest $request)
    {
        $data = $request->validated();

        $student = User::query()
            ->where('id', $data['student_id'])
            ->where('role', 'student')
            ->firstOrFail();

        $student->allergens()->sync($data['allergen_ids']);

        return response()->noContent();
    }
}
