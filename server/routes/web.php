<?php

use App\Http\Controllers\ZnaiBotFrontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
Route::get('/', [ZnaiBotFrontend::class, 'home'])->name('home');
Route::get('/scan', [ZnaiBotFrontend::class, 'scan'])->name('scan');
Route::post('/scan', [ZnaiBotFrontend::class, 'scanLogin'])->name('scan.login');

Route::get('/clubs', [ZnaiBotFrontend::class, 'clubs'])->name('clubs');
Route::get('/history', [ZnaiBotFrontend::class, 'history'])->name('history');
Route::get('/achievements', [ZnaiBotFrontend::class, 'achievements'])->name('achievements');
Route::get('/clubs/achievements/{id?}', [ZnaiBotFrontend::class, 'clubsAchievements'])->name('clubs_achievements');
Route::get('/clubs/gallery/{id}', [ZnaiBotFrontend::class, 'clubsGallery'])->name('clubs_gallery');
Route::get('/news', [ZnaiBotFrontend::class, 'news'])->name('news');
Route::get('/news/article/{slug}', [ZnaiBotFrontend::class, 'newsarticle'])->name('newsarticle');
Route::get('/robohead', [ZnaiBotFrontend::class, 'roboHead'])->name('robohead');

// --- STUDENT ROUTES ---
Route::middleware('nfc.scanned:student')->group(function () {
    Route::get('/student/home', [ZnaiBotFrontend::class, 'studentHome'])->name('student_home');
    Route::get('/student/lostthings', [ZnaiBotFrontend::class, 'studentLostthings'])->name('student_lostthings');
    
    // Quiz
    Route::get('/student/quiz', [ZnaiBotFrontend::class, 'studentQuiz'])->name('student_quiz');
    Route::post('/student/quiz/submit', [ZnaiBotFrontend::class, 'submitQuizAnswer'])->name('student_quiz_submit');

    // Chat / Teacher Search
    Route::get('/student/findteacher', [ZnaiBotFrontend::class, 'studentFindteacher'])->name('student_findteacher');
    Route::post('/student/find-teacher/ask', [ZnaiBotFrontend::class, 'searchTeacherLocation'])->name('ai.teacher.find');

    // General AI Chat
    Route::get('/student/chat', [ZnaiBotFrontend::class, 'studentChat'])->name('ai.chat');
    Route::post('/student/chat/ask', [ZnaiBotFrontend::class, 'askAi'])->name('ai.ask');

});

// --- PARENT ROUTES ---
Route::middleware('nfc.scanned:parent')->group(function () {
    Route::get('/parent/home', [ZnaiBotFrontend::class, 'parentHome'])->name('parent_home');
    Route::get('/parent/classroom', [ZnaiBotFrontend::class, 'parentClassroom'])->name('parent_classroom');
    Route::get('/parent/events', [ZnaiBotFrontend::class, 'parentEvents'])->name('parent_events');
    Route::get('/parent/route', [ZnaiBotFrontend::class, 'parentRoute'])->name('parent_route');
    Route::get('/parent/navigation', [ZnaiBotFrontend::class, 'parentNavigation'])->name('parent.navigation');
    Route::post('/parent/navigation/ask', [ZnaiBotFrontend::class, 'askParentNavigation'])->name('api.parent.navigation');
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

Route::get('/parent/ajax/student-location', [ZnaiBotFrontend::class, 'getStudentLocationApi'])->name('api.student.location');

require __DIR__.'/auth.php';