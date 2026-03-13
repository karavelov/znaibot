<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ClubDataTable;
use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Gallery;
use App\Models\User;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    use ImageUploadTrait;

    public function index(ClubDataTable $dataTable)
    {
        return $dataTable->render('admin.clubs.index');
    }

    public function create()
    {
        $galleries = Gallery::where('status', 1)->get();
        return view('admin.clubs.create', compact('galleries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'max:200'],
            'members'      => ['nullable', 'string', 'max:255'],
            'icon'         => ['nullable', 'image', 'max:3000'],
            'about'        => ['nullable'],
            'achievements' => ['nullable'],
            'gallery_id'   => ['nullable', 'exists:galleries,id'],
            'status'       => ['required', 'in:0,1'],
        ]);

        $club = new Club();
        $club->name         = $request->name;
        $club->members      = $request->members;
        $club->about        = $request->about;
        $club->achievements = $request->achievements;
        $club->gallery_id   = $request->gallery_id;
        $club->status       = $request->status;

        $iconPath = $this->uploadImage($request, 'icon', true);
        $club->icon = $iconPath;

        $club->save();

        toastr('Успешно създаден клуб', 'success', 'Успешно!');
        return redirect()->route('admin.clubs.index');
    }

    public function edit(string $id)
    {
        $club      = Club::with('students')->findOrFail($id);
        $galleries = Gallery::where('status', 1)->get();

        // Всички ученици, които още НЕ са в клуба
        $memberIds      = $club->students->pluck('id');
        $availableStudents = User::where('role', 'student')
            ->whereNotIn('id', $memberIds)
            ->orderBy('name')
            ->get();

        return view('admin.clubs.edit', compact('club', 'galleries', 'availableStudents'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'         => ['required', 'max:200'],
            'members'      => ['nullable', 'string', 'max:255'],
            'icon'         => ['nullable', 'image', 'max:3000'],
            'about'        => ['nullable'],
            'achievements' => ['nullable'],
            'gallery_id'   => ['nullable', 'exists:galleries,id'],
            'status'       => ['required', 'in:0,1'],
        ]);

        $club = Club::findOrFail($id);
        $club->name         = $request->name;
        $club->members      = $request->members;
        $club->about        = $request->about;
        $club->achievements = $request->achievements;
        $club->gallery_id   = $request->gallery_id;
        $club->status       = $request->status;

        $iconPath = $this->updateImage($request, 'icon', false, $club->icon);
        $club->icon = empty(!$iconPath) ? $iconPath : $club->icon;

        $club->save();

        toastr('Успешно редактиран клуб', 'success', 'Успешно!');
        return redirect()->route('admin.clubs.index');
    }

    public function destroy(string $id)
    {
        $club = Club::findOrFail($id);
        $this->deleteImage($club->icon);
        $club->delete();

        return response(['status' => 'success', 'message' => 'Изтрито успешно!']);
    }

    public function changeStatus(Request $request)
    {
        $club = Club::findOrFail($request->id);
        $club->status = $request->status == 'true' ? 1 : 0;
        $club->save();

        return response(['message' => 'Статусът е актуализиран успешно!']);
    }

    public function addStudent(Request $request, string $id)
    {
        $request->validate(['user_id' => ['required', 'exists:users,id']]);

        $club = Club::findOrFail($id);

        if ($club->students()->where('user_id', $request->user_id)->exists()) {
            return response(['status' => 'error', 'message' => 'Ученикът вече е член на клуба!'], 422);
        }

        $club->students()->attach($request->user_id);

        $student = User::find($request->user_id);

        return response([
            'status'  => 'success',
            'message' => 'Ученикът е добавен успешно!',
            'student' => [
                'id'        => $student->id,
                'name'      => $student->name,
                'email'     => $student->email,
                'edit_url'  => route('admin.users.edit', $student->id),
                'remove_url'=> route('admin.clubs.remove-student', [$id, $student->id]),
            ],
        ]);
    }

    public function removeStudent(string $id, string $userId)
    {
        $club = Club::findOrFail($id);
        $club->students()->detach($userId);

        return response(['status' => 'success', 'message' => 'Ученикът е премахнат от клуба!']);
    }
}
