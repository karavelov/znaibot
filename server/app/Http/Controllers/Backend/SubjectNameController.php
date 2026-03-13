<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SubjectNameDataTable;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectNameController extends Controller
{
    public function index(SubjectNameDataTable $dataTable)
    {
        return $dataTable->render('admin.subject-names.index');
    }

    public function create()
    {
        return view('admin.subject-names.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name'],
        ], [
            'name.required' => 'Полето Предмет е задължително!',
            'name.unique'   => 'Предмет с това наименование вече съществува!',
        ]);

        Subject::create(['name' => trim($request->name)]);

        toastr('Успешно добавен предмет', 'success', 'Успешно!');
        return redirect()->route('admin.subject-names.index');
    }

    public function edit(string $id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.subject-names.edit', compact('subject'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name,' . $id],
        ], [
            'name.required' => 'Полето Предмет е задължително!',
            'name.unique'   => 'Предмет с това наименование вече съществува!',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->name = trim($request->name);
        $subject->save();

        toastr('Успешно редактиран предмет', 'success', 'Успешно!');
        return redirect()->route('admin.subject-names.index');
    }

    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);

        if ($subject->assignments()->count() > 0) {
            return response([
                'status'  => 'error',
                'message' => 'Не може да изтриете предмет с асоциирани учители! Първо премахнете назначенията.',
            ], 422);
        }

        $subject->delete();
        return response(['status' => 'success', 'message' => 'Изтрито успешно!']);
    }
}
