<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\QuizCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\QuizCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QuizCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.quiz.quiz-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.quiz.quiz-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200', 'unique:quiz_categories']
        ], [
            'name.unique' => 'Тази категория вече съществува!',
            'name.required' => 'Полето е задължително!',
            'name.max' => 'Максимум 200 символа!'
        ]);

        $category = new QuizCategory();

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;

        $category->save();

        toastr('Успешно добавяне', 'success', 'Успешно добавяне!');

        return redirect()->route('admin.quiz-category.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = QuizCategory::findOrFail($id);
        return view('admin.quiz.quiz-category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'max:200', 'unique:quiz_categories,name,' . $id]
        ], [
            'name.unique' => 'Тази категория вече съществува!',
            'name.required' => 'Полето е задължително!',
            'name.max' => 'Максимум 200 символа!'
        ]);

        $category = QuizCategory::findOrFail($id);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;

        $category->save();

        toastr('Успешно редактиране', 'success', 'Успешно редактиране!');

        return redirect()->route('admin.quiz-category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = QuizCategory::findOrFail($id);

        $category->delete();

        return response(['status' => 'success', 'message' => 'Успешно изтриване!']);
    }

    public function changeStatus(Request $request)
    {
        $category = QuizCategory::findOrFail($request->id);
        $category->status = $request->status == 'true' ? 1 : 0;
        $category->save();

        return response(['message' => 'Статусът е успешно актуализиран!']);
    }
}
