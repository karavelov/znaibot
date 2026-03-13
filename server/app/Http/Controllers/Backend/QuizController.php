<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\QuizDataTable;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizCategory;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QuizDataTable $dataTable)
    {
        return $dataTable->render('admin.quiz.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories=QuizCategory::all();

        return view('admin.quiz.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:200'],
            'description' => ['nullable', 'string'],
            'status' => ['required'],
            'category_id' => ['required'],
            'time' => ['required', 'integer', 'min:1'], // Time in minutes
        ]);

        $quiz = new Quiz();

        $quiz->title = $request->title;
        $quiz->category_id = $request->category_id;
        $quiz->slug = \Str::slug($request->title);
        $quiz->description = $request->description;
        $quiz->published = $request->status;
        $quiz->public = 0;
        $quiz->time = $request->time; // Save time in minutes

        $quiz->save();

        toastr('Quiz successfully created', 'success', 'Success!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $categories=QuizCategory::all();

        return view('admin.quiz.edit', compact('quiz','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'max:200'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
            'category_id' => ['required'],
            'time' => ['required', 'integer', 'min:1'], // Time in minutes
        ]);

        $quiz = Quiz::findOrFail($id);

        $quiz->title = $request->title;
        $quiz->category_id = $request->category_id;
        $quiz->slug = \Str::slug($request->title);
        $quiz->description = $request->description;
        $quiz->published = $request->status;
        $quiz->public = 0;
        $quiz->time = $request->time; // Save time in minutes

        $quiz->save();

        toastr('Quiz successfully updated', 'success', 'Success!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quiz = Quiz::findOrFail($id);

        // Delete all tests associated with the quiz and their answers
        $quiz->tests()->each(function ($test) {
            // Delete all answers linked to the test
            $test->answers()->delete();
            // Delete the test itself
            $test->delete();
        });

        // Detach all questions from the quiz (pivot table)
        $quiz->questions()->detach();

        // Delete the quiz
        $quiz->delete();

        return response(['status' => 'success', 'message' => 'Тестът е изтрит успешно!']);
    }

    public function changeStatus(Request $request)
    {

        $quiz = Quiz::findOrFail($request->id);

        $quiz->published = $request->status == "true" ? 1 : 0;

        $quiz->save();

        return response(['status' => 'success', 'message' => 'Статусът е актуализиран успешно!']);
    }

    public function changePublicStatus(Request $request)
    {

        $quiz = Quiz::findOrFail($request->id);

        $quiz->public = $request->status == "true" ? 1 : 0;

        $quiz->save();

        return response(['status' => 'success', 'message' => 'Статусът е актуализиран успешно!']);
    }
}
