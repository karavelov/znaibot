<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ZnaiBotClientController;

Route::get('/', [ZnaiBotClientController::class, 'home'])->name('home');
Route::get('/scan', [ZnaiBotClientController::class, 'scan'])->name('scan');
Route::post('/scan', [ZnaiBotClientController::class, 'scanLogin'])->name('scan.login');

Route::get('/clubs', [ZnaiBotClientController::class, 'clubs'])->name('clubs');
Route::get('/history', [ZnaiBotClientController::class, 'history'])->name('history');
Route::get('/achievements', [ZnaiBotClientController::class, 'achievements'])->name('achievements');
Route::get('/clubs/achievements/{id?}', [ZnaiBotClientController::class, 'clubsAchievements'])->name('clubs_achievements');
Route::get('/clubs/gallery/{id}', [ZnaiBotClientController::class, 'clubsGallery'])->name('clubs_gallery');
Route::get('/news', [ZnaiBotClientController::class, 'news'])->name('news');
Route::get('/news/article/{slug}', [ZnaiBotClientController::class, 'newsarticle'])->name('newsarticle');
Route::get('/robohead', [ZnaiBotClientController::class, 'roboHead'])->name('robohead');

// --- STUDENT ROUTES ---
Route::middleware('nfc.scanned:student')->group(function () {
    Route::get('/student/home', [ZnaiBotClientController::class, 'studentHome'])->name('student_home');
    Route::get('/student/games', [ZnaiBotClientController::class, 'studentGames'])->name('student_games');
    Route::get('/student/lostthings', [ZnaiBotClientController::class, 'studentLostthings'])->name('student_lostthings');
    
    // Quiz
    Route::get('/student/quiz', [ZnaiBotClientController::class, 'studentQuiz'])->name('student_quiz');
    Route::post('/student/quiz/submit', [ZnaiBotClientController::class, 'submitQuizAnswer'])->name('student_quiz_submit');

    // Chat / Teacher Search
    Route::get('/student/findteacher', [ZnaiBotClientController::class, 'studentFindteacher'])->name('student_findteacher');
    Route::post('/student/find-teacher/ask', [ZnaiBotClientController::class, 'searchTeacherLocation'])->name('ai.teacher.find');

    // General AI Chat
    Route::get('/student/chat', [ZnaiBotClientController::class, 'studentChat'])->name('ai.chat');
    Route::post('/student/chat/ask', [ZnaiBotClientController::class, 'askAi'])->name('ai.ask');

});

// --- PARENT ROUTES ---
Route::middleware('nfc.scanned:parent')->group(function () {
    Route::get('/parent/home', [ZnaiBotClientController::class, 'parentHome'])->name('parent_home');
    Route::get('/parent/classroom', [ZnaiBotClientController::class, 'parentClassroom'])->name('parent_classroom');
    Route::get('/parent/events', [ZnaiBotClientController::class, 'parentEvents'])->name('parent_events');
    Route::get('/parent/route', [ZnaiBotClientController::class, 'parentRoute'])->name('parent_route');
    Route::get('/parent/navigation', [ZnaiBotClientController::class, 'parentNavigation'])->name('parent.navigation');
    Route::post('/parent/navigation/ask', [ZnaiBotClientController::class, 'askParentNavigation'])->name('api.parent.navigation');
});

// --- LOGOUT ---
Route::get('/logout', function (Request $request) {
    $request->session()->forget([
        'nfc_scanned',
        'nfc_card_id',
        'nfc_role',
        'user_id'
    ]);

    return redirect()->route('home');
})->name('logout');

Route::get('/parent/ajax/student-location', [ZnaiBotClientController::class, 'getStudentLocationApi'])->name('api.student.location');
