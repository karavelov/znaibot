<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\QuestionDataTable;
use App\Http\Controllers\Controller;
use App\Models\Question; // Увери се, че това е правилният модел
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(QuestionDataTable $dataTable)
    {
        return $dataTable->render('admin.questions.index');
    }

    public function create()
    {
        return view('admin.questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'klas' => 'required|integer|min:1|max:12',
            'points' => 'required|integer|min:1',
        ]);

        // Вече записва директно в questions_new
        $question = new Question();
        $question->question = $request->question;
        $question->klas = $request->klas;
        $question->points = $request->points;
        $question->save();

        toastr('Успешно добавен въпрос в новата таблица!', 'success');

        return redirect()->route('admin.questions.index');
    }

    public function edit(string $id)
    {
        // Търси в questions_new
        $question = Question::findOrFail($id);
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'question' => 'required|string',
            'klas' => 'required|integer|min:1|max:12',
            'points' => 'required|integer|min:1',
        ]);

        $question = Question::findOrFail($id);
        $question->question = $request->question;
        $question->klas = $request->klas;
        $question->points = $request->points;
        $question->save();

        toastr('Въпросът е обновен успешно!', 'success');

        return redirect()->route('admin.questions.index');
    }

    public function destroy(string $id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response(['status' => 'success', 'message' => 'Изтрито от questions_new!']);
    }

    public function search(Request $request)
    {
        $search = $request->input('q');

        // Търсенето също ще минава през questions_new
        $questions = Question::where('question', 'LIKE', "%{$search}%")
            ->select('id', 'question as text')
            ->get();

        return response()->json($questions);
    }
}