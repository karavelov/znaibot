<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Club;

class MainController extends Controller
{
    public function home() {
        return view('welcome');
    }

    public function clubs() {
        $clubs = Club::all();
        return view('clubs', compact('clubs'));
    }

    public function history() {
        return view('history');
    }

    public function achievements() {
        return view('achievements');
    }

    public function scan() {
        return view('scan');
    }

    public function studentDashboard() {
        return view('student');
    }

    public function parentDashboard() {
        return view('parent');
    }

public function askAi(Request $request) {
    $userPrompt = $request->input('prompt');

    try {
        // Пътят до твоя скрипт
        $scriptPath = "/root/ZnaiBotRag/znaibotrag.py";
        
        // Изпълняваме скрипта и вземаме крайния отговор от RAG
        // escapeshellarg е важно за сигурност!
        $command = "python3 " . $scriptPath . " " . escapeshellarg($userPrompt);
        $answer = shell_exec($command);

        if ($answer) {
            return response()->json([
                'answer' => trim($answer)
            ]);
        } else {
            throw new \Exception("Скриптът не върна резултат.");
        }

    } catch (\Exception $e) {
        return response()->json([
            'answer' => 'Грешка при връзка с RAG системата: ' . $e->getMessage()
        ], 500);
    }
}
}