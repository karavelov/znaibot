<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;

class ResultListController extends Controller
{
    public function index()
    {
        $tests = Test::select('id', 'result', 'time_spent', 'user_id', 'quiz_id', 'created_at', 'total_points')
        ->where('user_id', auth()->id())
        ->with(['quiz' => function ($query) {
            $query->select('id', 'title', 'description');
            $query->withCount('questions');
        }])
        ->orderBy('total_points', 'desc') // Order by most points
        ->paginate();

        return view('livewire.front.results.result-list', [
            'tests' => $tests
        ]);
    }
}
