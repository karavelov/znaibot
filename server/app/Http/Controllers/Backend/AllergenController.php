<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\AllergenDataTable;
use App\Http\Controllers\Controller;
use App\Models\Allergen;
use App\Models\User;
use Illuminate\Http\Request;

class AllergenController extends Controller
{
    public function index(AllergenDataTable $dataTable)
    {
        return $dataTable->render('admin.allergens.index');
    }

    public function create()
    {
        return view('admin.allergens.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:allergens,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'color'       => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        Allergen::create($request->only('name', 'description', 'color'));

        return redirect()->route('admin.allergens.index')
            ->with('success', 'Алергенът е добавен успешно.');
    }

    public function edit(Allergen $allergen)
    {
        return view('admin.allergens.edit', compact('allergen'));
    }

    public function update(Request $request, Allergen $allergen)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:allergens,name,' . $allergen->id],
            'description' => ['nullable', 'string', 'max:500'],
            'color'       => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $allergen->update($request->only('name', 'description', 'color'));

        return redirect()->route('admin.allergens.index')
            ->with('success', 'Алергенът е обновен успешно.');
    }

    public function destroy(Allergen $allergen)
    {
        $allergen->delete();

        return redirect()->route('admin.allergens.index')
            ->with('success', 'Алергенът е изтрит.');
    }

    // Dashboard — преглед за мед. сестра / стол
    public function dashboard(Request $request)
    {
        $search = $request->input('search', '');

        // По алерген — всеки алерген с потребителите му
        $allergens = Allergen::withCount('users')
            ->with(['users' => function ($q) use ($search) {
                $q->with('klas')->orderBy('name');
                if ($search) {
                    $q->where('name', 'like', "%{$search}%");
                }
            }])
            ->orderBy('name')
            ->get()
            ->filter(fn($a) => $a->users->isNotEmpty());

        // По потребител — всички потребители с алергени
        $usersQuery = User::whereHas('allergens')
            ->with(['allergens', 'klas'])
            ->orderBy('name');

        if ($search) {
            $usersQuery->where('name', 'like', "%{$search}%");
        }

        $usersWithAllergens = $usersQuery->get();

        return view('admin.allergens.dashboard', compact(
            'allergens', 'usersWithAllergens', 'search'
        ));
    }

    // AJAX — добавяне на алерген към потребител
    public function addUserAllergen(Request $request, User $user)
    {
        $request->validate([
            'allergen_id' => ['required', 'exists:allergens,id'],
            'notes'       => ['nullable', 'string', 'max:255'],
        ]);

        if ($user->allergens()->where('allergen_id', $request->allergen_id)->exists()) {
            return response()->json(['error' => 'Алергенът вече е добавен.'], 422);
        }

        $user->allergens()->attach($request->allergen_id, [
            'notes' => $request->notes ?: null,
        ]);

        $allergen = Allergen::find($request->allergen_id);

        return response()->json([
            'id'         => $allergen->id,
            'name'       => $allergen->name,
            'color'      => $allergen->color,
            'notes'      => $request->notes,
            'remove_url' => route('admin.allergens.user.remove', [$user->id, $allergen->id]),
        ]);
    }

    // AJAX — премахване на алерген от потребител
    public function removeUserAllergen(User $user, Allergen $allergen)
    {
        $user->allergens()->detach($allergen->id);

        return response()->json(['ok' => true]);
    }
}
