<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SubjectDataTable;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(SubjectDataTable $dataTable)
    {
        return $dataTable->render('admin.subjects.index');
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        return view('admin.subjects.create', compact('subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => ['required', 'string', 'max:255'],
            'teacher_id'   => ['required', 'exists:users,id'],
        ], [
            'subject_name.required' => 'Полето Предмет е задължително!',
            'teacher_id.required'   => 'Моля изберете учител!',
            'teacher_id.exists'     => 'Избраният учител не съществува!',
        ]);

        // Намери или създай предмет по име
        $subject = Subject::firstOrCreate(['name' => trim($request->subject_name)]);

        // Провери дали вече съществува тази комбинация
        $exists = SubjectTeacher::where('subject_id', $subject->id)
            ->where('teacher_id', $request->teacher_id)
            ->exists();

        if ($exists) {
            toastr('Тази комбинация предмет—учител вече съществува!', 'error', 'Грешка!');
            return redirect()->back()->withInput();
        }

        SubjectTeacher::create([
            'subject_id' => $subject->id,
            'teacher_id' => $request->teacher_id,
        ]);

        toastr('Успешно добавен предмет', 'success', 'Успешно!');
        return redirect()->route('admin.subjects.index');
    }

    public function edit(string $id)
    {
        $assignment = SubjectTeacher::with(['subject', 'teacher'])->findOrFail($id);
        $subjects   = Subject::orderBy('name')->get();
        $teachers   = User::where('role', 'teacher')->orderBy('name')->get();
        return view('admin.subjects.edit', compact('assignment', 'subjects', 'teachers'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'subject_name' => ['required', 'string', 'max:255'],
            'teacher_id'   => ['required', 'exists:users,id'],
        ], [
            'subject_name.required' => 'Полето Предмет е задължително!',
            'teacher_id.required'   => 'Моля изберете учител!',
        ]);

        $assignment = SubjectTeacher::findOrFail($id);

        $subject = Subject::firstOrCreate(['name' => trim($request->subject_name)]);

        // Провери дали вече съществува тази комбинация (без текущия запис)
        $exists = SubjectTeacher::where('subject_id', $subject->id)
            ->where('teacher_id', $request->teacher_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            toastr('Тази комбинация предмет—учител вече съществува!', 'error', 'Грешка!');
            return redirect()->back()->withInput();
        }

        $assignment->subject_id = $subject->id;
        $assignment->teacher_id = $request->teacher_id;
        $assignment->save();

        toastr('Успешно редактиран предмет', 'success', 'Успешно!');
        return redirect()->route('admin.subjects.index');
    }

    public function destroy(string $id)
    {
        SubjectTeacher::findOrFail($id)->delete();
        return response(['status' => 'success', 'message' => 'Изтрито успешно!']);
    }
}
