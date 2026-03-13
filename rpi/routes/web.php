<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ZnaiBotClientController;

Route::get('/check-scan', function (Request $request) {
    $cardId = Cache::pull('last_nfc_card_id');

    if ($cardId) {
        $user = DB::table('users')
                  ->where('nfc_id', $cardId)
                  ->where('status', 'active')
                  ->first();

        if ($user) {
            $request->session()->put('nfc_scanned', true);
            $request->session()->put('nfc_card_id', $cardId);
            $request->session()->put('nfc_role', $user->role);
            $request->session()->put('user_id', $user->id);

            return response()->json([
                'found' => true,
                'card_id' => $cardId, 
                'role' => $user->role
            ]);
        }
    }

    return response()->json(['found' => false, 'card_id' => null]);
})->name('check_scan');

Route::get('/', [ZnaiBotClientController::class, 'home'])->name('home');

Route::get('/scan', function (Request $request) {
    if ($request->session()->get('nfc_scanned')) {
        $role = $request->session()->get('nfc_role');
        if ($role === 'student') return redirect()->route('student_home');
        if ($role === 'parent') return redirect()->route('parent_home');
    }
    return app(ZnaiBotClientController::class)->scan($request);
})->name('scan');

Route::post('/debug/force-login/{role}', function (Request $request, $role) {
    $user = DB::table('users')->where('role', $role)->where('status', 'active')->first();
    if (!$user) return dd("Error: No user found");
    $request->session()->put('nfc_scanned', true);
    $request->session()->put('nfc_card_id', $user->nfc_id ?? 'DEBUG_ID');
    $request->session()->put('nfc_role', $user->role);
    $request->session()->put('user_id', $user->id);
    return ($role === 'student') ? redirect()->route('student_home') : redirect()->route('parent_home');
})->name('debug.login');

Route::get('/clubs', [ZnaiBotClientController::class, 'clubs'])->name('clubs');
Route::get('/history', [ZnaiBotClientController::class, 'history'])->name('history');
Route::get('/clubs/achievements/{id?}', [ZnaiBotClientController::class, 'clubsAchievements'])->name('clubs_achievements');
Route::get('/clubs/gallery/{id}', [ZnaiBotClientController::class, 'clubsGallery'])->name('clubs_gallery');
Route::get('/news', [ZnaiBotClientController::class, 'news'])->name('news');
Route::get('/news/article/{slug}', [ZnaiBotClientController::class, 'newsarticle'])->name('newsarticle');
Route::get('/robohead', [ZnaiBotClientController::class, 'roboHead'])->name('robohead');

Route::middleware('nfc.scanned:student')->group(function () {
    Route::get('/student/home', [ZnaiBotClientController::class, 'studentHome'])->name('student_home');
    Route::get('/student/games', [ZnaiBotClientController::class, 'studentGames'])->name('student_games');
    Route::get('/student/quiz', [ZnaiBotClientController::class, 'studentQuiz'])->name('student_quiz');
    Route::post('/student/quiz/submit', [ZnaiBotClientController::class, 'submitQuizAnswer'])->name('student_quiz_submit');
    Route::get('/student/lostthings', [ZnaiBotClientController::class, 'studentLostthings'])->name('student_lostthings');
    Route::get('/student/findteacher', [ZnaiBotClientController::class, 'studentFindteacher'])->name('student_findteacher');
    Route::get('/student/chat', [ZnaiBotClientController::class, 'studentChat'])->name('ai.chat');
    Route::post('/student/chat/ask', [ZnaiBotClientController::class, 'askAi'])->name('ai.ask');
    Route::post('/student/chat/stt/start', [ZnaiBotClientController::class, 'startRemoteStt'])->name('stt.start');
    Route::get('/student/chat/stt/result/{sessionId}', [ZnaiBotClientController::class, 'getSttResult'])->name('stt.result');
    Route::get('/student/chat/tts', [ZnaiBotClientController::class, 'proxyTts'])->name('tts.proxy');
    Route::post('/student/find-teacher/ask', [ZnaiBotClientController::class, 'searchTeacherLocation'])->name('ai.teacher.find');
});

Route::middleware('nfc.scanned:parent')->group(function () {
    Route::get('/parent/home', [ZnaiBotClientController::class, 'parentHome'])->name('parent_home');
    Route::get('/parent/classroom', [ZnaiBotClientController::class, 'parentClassroom'])->name('parent_classroom');
    Route::get('/parent/events', [ZnaiBotClientController::class, 'parentEvents'])->name('parent_events');
    Route::get('/parent/route', [ZnaiBotClientController::class, 'parentRoute'])->name('parent_route');
    Route::get('/parent/navigation', [App\Http\Controllers\ZnaiBotClientController::class, 'parentNavigation'])->name('parent.navigation');
Route::post('/parent/navigation/ask', [App\Http\Controllers\ZnaiBotClientController::class, 'askParentNavigation'])->name('api.parent.navigation');
});

Route::get('/logout', function (Request $request) {
    $request->session()->forget(['nfc_scanned', 'nfc_card_id', 'nfc_role', 'user_id']);
    return redirect()->route('home');
})->name('logout');

Route::get('/parent/ajax/student-location', [App\Http\Controllers\ZnaiBotClientController::class, 'getStudentLocationApi'])->name('api.student.location');
