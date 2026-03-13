<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\KlasDataTable;
use App\Http\Controllers\Controller;
use App\Models\Klas;
use App\Models\User;
use Illuminate\Http\Request;

class KlasController extends Controller
{
    public function index(KlasDataTable $dataTable)
    {
        return $dataTable->render('admin.klasses.index');
    }

    public function create()
    {
        return view('admin.klasses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:klasses,title'],
        ], [
            'title.required' => 'Полето Клас е задължително!',
            'title.unique'   => 'Вече съществува клас с това наименование!',
        ]);

        Klas::create(['title' => $request->title]);

        toastr('Успешно създаден клас', 'success', 'Успешно!');
        return redirect()->route('admin.klasses.index');
    }

    public function edit(string $id)
    {
        $klas = Klas::findOrFail($id);
        return view('admin.klasses.edit', compact('klas'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:klasses,title,' . $id],
        ], [
            'title.required' => 'Полето Клас е задължително!',
            'title.unique'   => 'Вече съществува клас с това наименование!',
        ]);

        $klas = Klas::findOrFail($id);
        $klas->title = $request->title;
        $klas->save();

        toastr('Успешно редактиран клас', 'success', 'Успешно!');
        return redirect()->route('admin.klasses.index');
    }

    public function destroy(string $id)
    {
        $klas = Klas::findOrFail($id);
        $klas->delete();

        return response(['status' => 'success', 'message' => 'Изтрито успешно!']);
    }

    public function removeStudent(string $klasId, string $userId)
    {
        $student = User::where('id', $userId)
            ->where('klas_id', $klasId)
            ->firstOrFail();

        $student->klas_id = null;
        $student->save();

        return response(['status' => 'success', 'message' => 'Ученикът е премахнат от класа!']);
    }

    public function removeHomeroom(string $klasId)
    {
        $teacher = User::where('homeroom_klas_id', $klasId)->firstOrFail();

        $teacher->homeroom_klas_id = null;
        $teacher->save();

        return response(['status' => 'success', 'message' => 'Класният ръководител е премахнат!']);
    }
}
