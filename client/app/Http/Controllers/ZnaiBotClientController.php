<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ZnaiBotClientController extends Controller
{
    private $schoolMapData = [
        '101' => ['floor' => 'firstfloor', 'x' => 33.90, 'y' => 46.02],
        '102' => ['floor' => 'firstfloor', 'x' => 33.90, 'y' => 54.27],
        '103' => ['floor' => 'firstfloor', 'x' => 33.90, 'y' => 63.15],
        '104' => ['floor' => 'firstfloor', 'x' => 33.90, 'y' => 71.68],
        '105' => ['floor' => 'firstfloor', 'x' => 33.90, 'y' => 80.41],
        '106' => ['floor' => 'firstfloor', 'x' => 33.90, 'y' => 89.08],
        '107' => ['floor' => 'firstfloor', 'x' => 16.45, 'y' => 25.62],
        '108' => ['floor' => 'firstfloor', 'x' => 16.45, 'y' => 21.42],
        '109' => ['floor' => 'firstfloor', 'x' => 16.45, 'y' => 14.92],
        '110' => ['floor' => 'firstfloor', 'x' => 16.45, 'y' => 8.28],
        '111' => ['floor' => 'firstfloor', 'x' => 28.73, 'y' => 7.79],
        '112' => ['floor' => 'firstfloor', 'x' => 28.73, 'y' => 14.36],
        '113' => ['floor' => 'firstfloor', 'x' => 28.73, 'y' => 20.30],
        '114' => ['floor' => 'firstfloor', 'x' => 28.73, 'y' => 24.36],
        '115' => ['floor' => 'firstfloor', 'x' => 33.90, 'y' => 28.13],
        'Охрана' => ['floor' => 'firstfloor', 'x' => 35.66, 'y' => 40.43],
        'Галерия' => ['floor' => 'firstfloor', 'x' => 49.97, 'y' => 39.18],
        'Склад' => ['floor' => 'firstfloor', 'x' => 55.33, 'y' => 29.25],
        'Физкултурен салон' => ['floor' => 'firstfloor', 'x' => 65.21, 'y' => 15.83],
        'Съблекалня' => ['floor' => 'firstfloor', 'x' => 70.57, 'y' => 30.65],
        'Стая за персонал' => ['floor' => 'firstfloor', 'x' => 16.91, 'y' => 28.69],
        '201' => ['floor' => 'secondfloor', 'x' => 33.90, 'y' => 46.02],
        '202' => ['floor' => 'secondfloor', 'x' => 33.90, 'y' => 54.27],
        '203' => ['floor' => 'secondfloor', 'x' => 33.90, 'y' => 63.15],
        '204' => ['floor' => 'secondfloor', 'x' => 33.90, 'y' => 71.68],
        '205' => ['floor' => 'secondfloor', 'x' => 33.90, 'y' => 80.41],
        '206' => ['floor' => 'secondfloor', 'x' => 33.90, 'y' => 89.08],
        'Библиотека' => ['floor' => 'secondfloor', 'x' => 16.45, 'y' => 25.62],
        '208' => ['floor' => 'secondfloor', 'x' => 16.54, 'y' => 24.64],
        '209' => ['floor' => 'secondfloor', 'x' => 16.45, 'y' => 17.30],
        '210' => ['floor' => 'secondfloor', 'x' => 16.45, 'y' => 10.45],
        '211' => ['floor' => 'secondfloor', 'x' => 28.92, 'y' => 8.35],
        '212' => ['floor' => 'secondfloor', 'x' => 28.92, 'y' => 12.20],
        '213' => ['floor' => 'secondfloor', 'x' => 28.92, 'y' => 17.79],
        '214' => ['floor' => 'secondfloor', 'x' => 28.92, 'y' => 25.83],
        '215' => ['floor' => 'secondfloor', 'x' => 42.95, 'y' => 36.59],
        '216' => ['floor' => 'secondfloor', 'x' => 47.39, 'y' => 36.59],
        '217' => ['floor' => 'secondfloor', 'x' => 51.45, 'y' => 36.59],
        '218' => ['floor' => 'secondfloor', 'x' => 58.19, 'y' => 37.99],
        '301' => ['floor' => 'thirdfloor', 'x' => 33.90, 'y' => 46.02],
        '302' => ['floor' => 'thirdfloor', 'x' => 33.90, 'y' => 54.27],
        '303' => ['floor' => 'thirdfloor', 'x' => 33.90, 'y' => 63.15],
        '304' => ['floor' => 'thirdfloor', 'x' => 33.90, 'y' => 71.68],
        '305' => ['floor' => 'thirdfloor', 'x' => 33.90, 'y' => 80.41],
        '306' => ['floor' => 'thirdfloor', 'x' => 33.90, 'y' => 89.08],
        'Офис' => ['floor' => 'thirdfloor', 'x' => 16.45, 'y' => 25.62],
        '308' => ['floor' => 'thirdfloor', 'x' => 16.54, 'y' => 24.64],
        '309' => ['floor' => 'thirdfloor', 'x' => 16.45, 'y' => 17.30],
        '310' => ['floor' => 'thirdfloor', 'x' => 16.45, 'y' => 10.45],
        '311' => ['floor' => 'thirdfloor', 'x' => 28.92, 'y' => 8.35],
        '312' => ['floor' => 'thirdfloor', 'x' => 28.92, 'y' => 12.20],
        '313' => ['floor' => 'thirdfloor', 'x' => 28.92, 'y' => 17.79],
        '314' => ['floor' => 'thirdfloor', 'x' => 28.92, 'y' => 25.83],
        '401' => ['floor' => 'forthfloor', 'x' => 33.90, 'y' => 46.02],
        '402' => ['floor' => 'forthfloor', 'x' => 33.90, 'y' => 54.27],
        '403' => ['floor' => 'forthfloor', 'x' => 33.90, 'y' => 63.15],
        '404' => ['floor' => 'forthfloor', 'x' => 33.90, 'y' => 71.68],
        '405' => ['floor' => 'forthfloor', 'x' => 33.90, 'y' => 80.41],
        '406' => ['floor' => 'forthfloor', 'x' => 33.90, 'y' => 89.08],
        '407' => ['floor' => 'forthfloor', 'x' => 16.45, 'y' => 25.62],
        '408' => ['floor' => 'forthfloor', 'x' => 16.54, 'y' => 24.64],
        '409' => ['floor' => 'forthfloor', 'x' => 16.45, 'y' => 17.30],
        '410' => ['floor' => 'forthfloor', 'x' => 16.45, 'y' => 10.45],
        '411' => ['floor' => 'forthfloor', 'x' => 28.92, 'y' => 8.35],
        '412' => ['floor' => 'forthfloor', 'x' => 28.92, 'y' => 12.20],
        '413' => ['floor' => 'forthfloor', 'x' => 28.92, 'y' => 17.79],
        '414' => ['floor' => 'forthfloor', 'x' => 28.92, 'y' => 25.83],
    ];

    public function home()
    {
        return view('home');
    }

    public function scan()
    {
        return view('scan');
    }

    public function scanLogin(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $login = trim((string) $validated['login']);

        $user = DB::table('users')
            ->where('status', 'active')
            ->whereIn('role', ['student', 'parent'])
            ->where(function ($query) use ($login) {
                $query->where('email', $login)
                    ->orWhere('username', $login);
            })
            ->first();

        if (!$user || !Hash::check((string) $validated['password'], (string) $user->password)) {
            return back()
                ->withErrors(['login' => 'Невалидни данни за вход.'])
                ->withInput();
        }

        $request->session()->put('nfc_scanned', true);
        $request->session()->put('nfc_card_id', $user->nfc_id ?? null);
        $request->session()->put('nfc_role', $user->role);
        $request->session()->put('user_id', $user->id);

        return $user->role === 'student'
            ? redirect()->route('student_home')
            : redirect()->route('parent_home');
    }

    public function news()
    {
        $featured = DB::table('blogs')->where('status', 1)->orderBy('created_at', 'desc')->first();
        $news = DB::table('blogs')->where('status', 1);
        if ($featured) {
            $news->where('id', '!=', $featured->id);
        }
        $news = $news->orderBy('created_at', 'desc')->get();
        return view('news', compact('featured', 'news'));
    }

    public function newsarticle($slug)
    {
        $article = DB::table('blogs')->where('slug', $slug)->where('status', 1)->first();
        abort_if(!$article, 404);
        return view('newsarticle', compact('article'));
    }

    public function clubs()
    {
        $clubs = DB::table('clubs')->get();
        return view('clubs', ['clubs' => $clubs]);
    }

    public function clubsAchievements()
    {
        $clubs = DB::table('clubs')->whereNotNull('achievements')->get();
        return view('clubs_achievements', compact('clubs'));
    }

    public function clubsGallery($id)
    {
        $gallery = DB::table('galleries')->where('id', $id)->first();
        $galleries = DB::table('galleries')->where('id', $id)->get();
        $galleriesimages = DB::table('gallery_images')->where('gallery_id', $id)->get();
        return view('clubs_gallery', compact('gallery', 'galleries', 'galleriesimages'));
    }

    public function history()
    {
        return view('history');
    }

    public function achievements()
    {
        return view('achievements');
    }

    public function roboHead()
    {
        return view('robohead');
    }

    public function studentHome()
    {
        return view('student_home', ['user' => $this->getScannedUser()]);
    }

    public function studentGames()
    {
        return view('back.student_games', [
            'user' => $this->getScannedUser(),
            'streamUrl' => env('RPS_STREAM_URL', 'https://adrianne-clandestine-maryellen.ngrok-free.dev/?token=znaibot_2026_parolata'),
        ]);
    }

    public function studentChat()
    {
        return view('student_chat', ['user' => $this->getScannedUser()]);
    }

    public function studentFindteacher()
    {
        return view('student_findteacher', ['user' => $this->getScannedUser()]);
    }

     public function getStudentLocationApi(Request $request)
    {
        $parentId = session('user_id');
        if (!$parentId) {
            return response()->json(['ok' => false, 'message' => 'Не сте влезли в профила си.']);
        }

        // Намиране на ученика
        $child = DB::table('users')
            ->leftJoin('klasses', 'users.klas_id', '=', 'klasses.id')
            ->select('users.name as student_name', 'klasses.title as klas_name')
            ->where('users.role', 'student')
            ->where(function($query) use ($parentId) {
                $query->where('users.parent_father_id', $parentId)
                      ->orWhere('users.parent_mother_id', $parentId);
            })
            ->first();

        if (!$child) {
            return response()->json(['ok' => false, 'message' => 'Не е намерен ученик, свързан с вашия профил.']);
        }

        $now = \Carbon\Carbon::now('Europe/Sofia');
        $currentTime = $now->format('H:i');
        $currentDay = mb_convert_case($now->locale('bg')->isoFormat('dddd'), MB_CASE_TITLE, "UTF-8");

        try {
            $response = Http::timeout(60)->post('http://192.168.0.253:11437/ask_student', [
                'student_name' => $child->student_name,
                'student_class' => $child->klas_name ?? 'Няма клас',
                'current_day' => $currentDay,
                'current_time' => $currentTime
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // ТЪРСЕНЕ В КАРТАТА
                $location = null;
                $roomNumber = trim($data['room'] ?? ''); // Чистим интервали
                
                // ЛОГ ЗА ДЕБЪГВАНЕ (виж го в storage/logs/laravel.log)
                Log::info("Parent Map Check: Searching for room '{$roomNumber}' in map data.");

                if (!empty($roomNumber) && isset($this->schoolMapData[$roomNumber])) {
                    $location = $this->schoolMapData[$roomNumber];
                    $location['room'] = $roomNumber;
                    Log::info("Parent Map Check: FOUND!");
                } else {
                    Log::info("Parent Map Check: NOT FOUND. Keys available: " . implode(',', array_keys($this->schoolMapData)));
                }

                return response()->json([
                    'ok' => true,
                    'student_name' => $child->student_name,
                    'klas_name' => $child->klas_name, 
                    'status' => $data['status'] ?? 'unknown',
                    'subject' => $data['subject'] ?? 'Неизвестен предмет',
                    'teacher' => $data['teacher'] ?? 'Неизвестен',
                    'room' => $roomNumber,
                    'end_time' => $data['end'] ?? '-',
                    'answer' => $data['answer'] ?? '',
                    'location' => $location
                ]);
            }

            return response()->json(['ok' => false, 'message' => 'Грешка от AI сървъра: ' . $response->status()]);

        } catch (\Throwable $e) {
            Log::error("Parent Fetch Error: " . $e->getMessage());
            return response()->json(['ok' => false, 'message' => 'Няма връзка с AI сървъра.']);
        }
    }

    public function studentLostthings()
    {
        $items = [];
        if (DB::getSchemaBuilder()->hasTable('lost_items')) {
            $items = DB::table('lost_items')->orderBy('created_at', 'desc')->get();
        }
        return view('student_lostthings', [
            'items' => $items,
            'user' => $this->getScannedUser()
        ]);
    }

    public function studentQuiz()
    {
        $user = $this->getScannedUser();
        if (!$user) return redirect()->route('scan');

        if (date('j') == 1) {
            $resetKey = 'quiz_points_reset_' . date('Y_m');
            if (!Cache::has($resetKey)) {
                DB::table('users')->where('role', 'student')->update(['quiz_points' => 0]);
                Cache::put($resetKey, true, now()->addDays(32));
            }
        }

        $today = date('Y-m-d');
        $answeredKey = 'quiz_answered_correctly_' . $user->id . '_' . $today;
        $attemptsKey = 'quiz_attempts_count_' . $user->id . '_' . $today;
        $attempts = Cache::get($attemptsKey, 0);

        $isDone = Cache::has($answeredKey) || $attempts >= 3;

        if ($isDone) {
            $scoreboard = DB::table('users')
                ->where('role', 'student')
                ->where('klas_id', $user->klas_id)
                ->orderByDesc('quiz_points')
                ->limit(10)
                ->get();
            return view('student_quiz', ['answered' => true, 'user' => $user, 'question' => null, 'scoreboard' => $scoreboard]);
        }

        $grade = (int) preg_replace('/[^0-9]/', '', ($user->klas_name ?? '0'));
        $dailyQuestionKey = 'daily_question_id_grade_' . $grade . '_' . $today;

        $questionId = Cache::remember($dailyQuestionKey, now()->endOfDay(), function () use ($grade) {
            return DB::table('questions_new')->where('klas', $grade)->inRandomOrder()->value('id');
        });

        $question = $questionId ? DB::table('questions_new')->find($questionId) : null;
        $attemptsLeft = 3 - $attempts;

        return view('student_quiz', ['question' => $question, 'user' => $user, 'answered' => false, 'attemptsLeft' => $attemptsLeft]);
    }

    public function submitQuizAnswer(Request $req)
    {
        $u = $this->getScannedUser();
        $q = DB::table('questions_new')->where('id', $req->input('question_id'))->first();
        if (!$u || !$q) return response()->json(['ok' => false]);

        $prompt = "Въпрос: \"{$q->question}\". Ученикът отговаря: \"{$req->input('answer')}\". Ти си учител. Ако отговорът е правилен по смисъл, напиши само думата 'YES'. Ако е грешен, напиши 'NO' и дай малка подсказка на български език без да казваш отговора.";

        try {
            $r = Http::timeout(30)->post('http://192.168.0.253:11434/api/generate', ['model' => 'Znaibot', 'prompt' => $prompt, 'stream' => false]);
            $ai = $r->json()['response'] ?? 'NO';
            $today = date('Y-m-d');

            if (str_contains(strtoupper($ai), 'YES')) {
                DB::table('users')->where('id', $u->id)->increment('quiz_points', (int)($q->points ?? 10));
                Cache::put('quiz_answered_correctly_' . $u->id . '_' . $today, true, now()->endOfDay());
                return response()->json(['ok' => true, 'correct' => true]);
            }

            $attemptsKey = 'quiz_attempts_count_' . $u->id . '_' . $today;
            $attempts = Cache::increment($attemptsKey);
            if ($attempts == 1) {
                Cache::put($attemptsKey, 1, now()->endOfDay());
            }

            return response()->json([
                'ok' => true,
                'correct' => false,
                'hint' => trim(str_replace(['NO', 'no', 'No'], '', $ai)),
                'out_of_attempts' => $attempts >= 3
            ]);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    public function parentHome()
    {
        $parentId = session('user_id');
        if (!$parentId) {
            return redirect()->route('home');
        }

        $parent = DB::table('users')->where('id', $parentId)->first();
        $child = DB::table('users')
            ->leftJoin('klasses', 'users.klas_id', '=', 'klasses.id')
            ->select('users.*', 'klasses.title as klas_name')
            ->where('users.role', 'student')
            ->where(function ($query) use ($parentId) {
                $query->where('users.parent_father_id', $parentId)
                    ->orWhere('users.parent_mother_id', $parentId);
            })
            ->first();

        return view('parent_home', [
            'parent' => $parent,
            'child' => $child
        ]);
    }

    public function parentEvents()
    {
        return view('parent_events');
    }

    public function parentClassroom()
    {
        return view('parent_classroom');
    }

    public function parentRoute()
    {
        return view('parent_route');
    }

    public function parentNavigation() {
        $user = $this->getScannedUser();
        if (!$user) {
             $parentId = session('user_id');
             if($parentId) {
                 $user = DB::table('users')->where('id', $parentId)->first();
                 $user->klas_name = 'Родител';
             }
        }
        return view('parent_navigation', ['user' => $user]);
    }

    public function askParentNavigation(Request $request)
    {
        $query = (string) $request->input('message', '');
        if (empty($query)) {
            return response()->json(['ok' => false, 'answer' => 'Моля, напишете въпрос.']);
        }

        try {
            $response = Http::timeout(30)->post('http://192.168.0.253:11438/navigate', [
                'query' => $query
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $answer = $data['answer'] ?? 'Нямам отговор.';
                $target = $data['target'] ?? null;
                $location = null;

                if ($target && isset($this->schoolMapData[$target])) {
                    $location = $this->schoolMapData[$target];
                    $location['room'] = $target;
                }

                if ($target == '218' && isset($this->schoolMapData['218'])) {
                    $location = $this->schoolMapData['218'];
                    $location['room'] = 'Учителска стая (218)';
                }

                return response()->json([
                    'ok' => true,
                    'answer' => $answer,
                    'location' => $location
                ]);
            }
            return response()->json(['ok' => false, 'answer' => 'Грешка при връзка с навигатора.']);
        } catch (\Throwable $e) {
            Log::error("Parent Nav Error: " . $e->getMessage());
            return response()->json(['ok' => false, 'answer' => 'Системна грешка.']);
        }
    }

    public function searchTeacherLocation(Request $request)
    {
        $schoolMapData = $this->schoolMapData;
        $userQuery = (string) $request->input('message', '');
        $now = Carbon::now('Europe/Sofia');
        $currentTime = $now->format('H:i');
        $currentDay = mb_convert_case($now->locale('bg')->isoFormat('dddd'), MB_CASE_TITLE, "UTF-8");

        $history = session()->get('teacher_search_history', []);
        if (!is_array($history)) {
            $history = [];
        }

        try {
            $response = Http::timeout(60)->post('http://192.168.0.253:11436/ask', [
                'query' => (string)$userQuery,
                'current_day' => (string)$currentDay,
                'current_time' => (string)$currentTime,
                'history' => []
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $answer = $data['answer'] ?? 'Нямам отговор.';
                $history[] = ['role' => 'user', 'content' => $userQuery];
                $history[] = ['role' => 'assistant', 'content' => $answer];
                session()->put('teacher_search_history', array_slice($history, -6));

                $location = null;
                if (preg_match('/([1-4][0-9]{2})/', $answer, $matches)) {
                    $roomNumber = $matches[1];
                    if (isset($schoolMapData[$roomNumber])) {
                        $location = $schoolMapData[$roomNumber];
                        $location['room'] = $roomNumber;
                    }
                }

                return response()->json(['ok' => true, 'answer' => $answer, 'location' => $location]);
            }
            return response()->json(['ok' => false, 'answer' => 'Грешка от AI сървъра.']);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['ok' => false, 'answer' => 'Грешка: ' . $e->getMessage()]);
        }
    }

    public function findTeacherAi(Request $request)
    {
        $query = (string) $request->input('message', '');
        $systemContext = "Ти си училищен асистент. Потребителят търси учител или стая. ";
        $result = $this->generateAiAnswer($systemContext . $query);
        return response()->json($result);
    }

    public function askAi(Request $request)
    {
        $result = $this->generateAiAnswer((string) $request->input('message', ''));
        return response()->json($result);
    }

    private function getScannedUser()
    {
        $userId = session('user_id');
        if (!$userId) return null;

        return DB::table('users')
            ->leftJoin('klasses', 'users.klas_id', '=', 'klasses.id')
            ->select('users.*', 'klasses.title as klas_name')
            ->where('users.id', $userId)
            ->first();
    }

    private function generateAiAnswer(string $userMessage): array
    {
        if (empty(trim($userMessage))) {
            return ['answer' => 'Моля, напишете въпрос.', 'ok' => false];
        }

        $user = $this->getScannedUser();
        $userName = $user ? $user->name : 'Ученик';
        $userClass = $user ? $user->klas_name : '10';

        $history = session()->get('chat_history', []);
        if (!is_array($history)) {
            $history = [];
        }

        $url = 'http://192.168.0.253:11435/ask';

        try {
            $response = Http::timeout(120)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'query' => (string) $userMessage,
                    'user_name' => (string) $userName,
                    'user_class' => (string) $userClass,
                    'history' => $history,
                ]);

            if ($response->successful()) {
                $answer = $response->json()['answer'] ?? 'Нямам отговор.';
                $history[] = ['role' => 'user', 'content' => (string)$userMessage];
                $history[] = ['role' => 'assistant', 'content' => (string)$answer];

                if (count($history) > 10) {
                    $history = array_slice($history, -10);
                }
                session()->put('chat_history', $history);

                return ['answer' => $answer, 'ok' => true];
            }

            Log::error("AI Server Error: " . $response->status() . " - " . $response->body());
            return ['answer' => 'AI сървърът върна грешка: ' . $response->status(), 'ok' => false];
        } catch (\Throwable $e) {
            Log::error("AI Connection Failed: " . $e->getMessage());
            return ['answer' => 'Няма връзка с AI сървъра.', 'ok' => false];
        }
    }
}