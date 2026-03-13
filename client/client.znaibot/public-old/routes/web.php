<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

Route::get('/', [MainController::class, 'home'])->name('welcome');

// Публична информация
Route::get('/clubs', [MainController::class, 'clubs'])->name('clubs');
Route::get('/history', [MainController::class, 'history'])->name('history');
Route::get('/achievements', [MainController::class, 'achievements'])->name('achievements');

// Сканиране
Route::get('/scan', [MainController::class, 'scan'])->name('scan');

// Табла след сканиране (Симулация)
Route::get('/dashboard/student', [MainController::class, 'studentDashboard'])->name('student');
Route::get('/dashboard/parent', [MainController::class, 'parentDashboard'])->name('parent');

Route::get('/student/chat', function () {
    return view('chat');
})->name('ai.chat');

Route::post('/student/chat/ask', [MainController::class, 'askAi'])->name('ai.ask');