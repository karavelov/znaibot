<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ZnaiBotClientController extends Controller
{
    private function sttStorePut(string $sessionId, array $payload): void
    {
        Cache::put("stt_result_{$sessionId}", $payload, now()->addMinutes(10));
    }

    private function sttStoreGet(string $sessionId): ?array
    {
        $cachePayload = Cache::get("stt_result_{$sessionId}");
        return is_array($cachePayload) ? $cachePayload : null;
    }

    private function mqttEncodeString(string $value): string
    {
        return pack('n', strlen($value)) . $value;
    }

    private function mqttEncodeRemainingLength(int $length): string
    {
        $encoded = '';
        do {
            $digit = $length % 128;
            $length = intdiv($length, 128);
            if ($length > 0) {
                $digit = $digit | 0x80;
            }
            $encoded .= chr($digit);
        } while ($length > 0);
        return $encoded;
    }

    private function mqttPublish(string $topic, string $payload): void
    {
        $host = env('MQTT_BROKER_HOST', '127.0.0.1');
        $port = (int) env('MQTT_BROKER_PORT', 1883);
        $username = env('MQTT_USERNAME');
        $password = env('MQTT_PASSWORD');
        $clientId = 'znaibot-web-' . substr((string) Str::uuid(), 0, 8);
        $socket = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 5);
        if (!$socket) {
            throw new \RuntimeException("MQTT connect failed: {$errstr} ({$errno})");
        }
        stream_set_timeout($socket, 5);
        $connectFlags = 0x02;
        if (!empty($username)) { $connectFlags |= 0x80; }
        if (!empty($password)) { $connectFlags |= 0x40; }
        $variableHeader = $this->mqttEncodeString('MQTT') . chr(0x04) . chr($connectFlags) . pack('n', 60);
        $connectPayload = $this->mqttEncodeString($clientId);
        if (!empty($username)) { $connectPayload .= $this->mqttEncodeString((string) $username); }
        if (!empty($password)) { $connectPayload .= $this->mqttEncodeString((string) $password); }
        $connectRemainingLength = strlen($variableHeader) + strlen($connectPayload);
        $connectPacket = chr(0x10) . $this->mqttEncodeRemainingLength($connectRemainingLength) . $variableHeader . $connectPayload;
        fwrite($socket, $connectPacket);
        $connAck = fread($socket, 4);
        if (strlen($connAck) < 4 || ord($connAck[0]) !== 0x20 || ord($connAck[3]) !== 0x00) {
            fclose($socket);
            throw new \RuntimeException('MQTT broker rejected connection');
        }
        $packetId = random_int(1, 65535);
        $publishVariableHeader = $this->mqttEncodeString($topic) . pack('n', $packetId);
        $publishBody = $publishVariableHeader . $payload;
        $publishPacket = chr(0x32) . $this->mqttEncodeRemainingLength(strlen($publishBody)) . $publishBody;
        fwrite($socket, $publishPacket);
        $pubAck = fread($socket, 4);
        if (strlen($pubAck) < 4 || ord($pubAck[0]) !== 0x40 || ord($pubAck[1]) !== 0x02) {
            fclose($socket);
            throw new \RuntimeException('MQTT publish not acknowledged');
        }
        fwrite($socket, chr(0xE0) . chr(0x00));
        fclose($socket);
    }

    private function buildTtsUrl(string $text): ?string
    {
        return route('tts.proxy', ['text' => $text]);
    }

    private function getScannedUser()
    {
        $user = Auth::user();
        if (!$user) return null;
        return DB::table('users')
            ->leftJoin('klasses', 'users.klas_id', '=', 'klasses.id')
            ->select('users.*', 'klasses.title as klas_name')
            ->where('users.id', $user->id)
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
        if (!is_array($history)) { $history = []; }
        $url = 'http://192.168.0.253:11435/ask';
        try {
            $response = Http::timeout(120)->post($url, [
                'query' => (string) $userMessage,
                'user_name' => (string) $userName,
                'user_class' => (string) $userClass,
                'history' => $history,
            ]);
            if ($response->successful()) {
                $answer = $response->json()['answer'] ?? 'Нямам отговор.';
                $history[] = ['role' => 'user', 'content' => (string)$userMessage];
                $history[] = ['role' => 'assistant', 'content' => (string)$answer];
                if (count($history) > 10) { $history = array_slice($history, -10); }
                session()->put('chat_history', $history);
                return ['answer' => $answer, 'tts_url' => $this->buildTtsUrl($answer), 'ok' => true];
            }
            return ['answer' => 'Грешка от AI сървъра.', 'ok' => false];
        } catch (\Throwable $e) {
            return ['answer' => 'Няма връзка с AI сървъра.', 'ok' => false];
        }
    }

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
    ];

    public function searchTeacherLocation(Request $request)
    {
        $userQuery = (string) $request->input('message', '');
        $now = Carbon::now('Europe/Sofia');
        $currentTime = $now->format('H:i');
        $currentDay = mb_convert_case($now->locale('bg')->isoFormat('dddd'), MB_CASE_TITLE, "UTF-8");
        try {
            $response = Http::timeout(60)->post('http://192.168.0.253:11436/ask', [
                'query' => $userQuery, 'current_day' => $currentDay, 'current_time' => $currentTime, 'history' => []
            ]);
            if ($response->successful()) {
                $answer = $response->json()['answer'] ?? 'Нямам отговор.';
                $location = null;
                if (preg_match('/([1-4][0-9]{2})/', $answer, $matches)) {
                    $room = $matches[1];
                    if (isset($this->schoolMapData[$room])) { $location = $this->schoolMapData[$room]; $location['room'] = $room; }
                }
                return response()->json(['ok' => true, 'answer' => $answer, 'location' => $location, 'tts_url' => route('tts.proxy', ['text' => $answer])]);
            }
            return response()->json(['ok' => false, 'answer' => 'Грешка от AI.']);
        } catch (\Throwable $e) { return response()->json(['ok' => false, 'answer' => 'Грешка: ' . $e->getMessage()]); }
    }

    public function home() { return view('home'); }

    public function findTeacherAi(Request $request)
    {
        $query = (string) $request->input('message', '');
        return response()->json($this->generateAiAnswer("Ти си училищен асистент. " . $query));
    }

    public function clubs() { return view('clubs', ['clubs' => DB::table('clubs')->get()]); }

    public function clubsAchievements($id = null)
    {
        $query = DB::table('clubs')->whereNotNull('achievements');
        if ($id) { $query->where('id', $id); }
        $clubs = $query->get();
        if ($id && $clubs->isEmpty()) { abort(404); }
        return view('clubs_achievements', compact('clubs'));
    }

    public function clubsGallery($id)
    {
        $gallery = DB::table('galleries')->where('id', $id)->first();
        $galleries = DB::table('galleries')->where('id', $id)->get();
        $galleriesimages = DB::table('gallery_images')->where('gallery_id', $id)->get();
        return view('clubs_gallery', compact('gallery', 'galleries', 'galleriesimages'));
    }

    public function history() { return view('history'); }
    public function achievements() { return view('achievements'); }
    public function scan() { return view('scan'); }
    public function news()
    {
        $featured = DB::table('blogs')->where('status', 1)->orderBy('created_at', 'desc')->first();
        $news = DB::table('blogs')->where('status', 1);
        if ($featured) { $news->where('id', '!=', $featured->id); }
        return view('news', ['featured' => $featured, 'news' => $news->orderBy('created_at', 'desc')->get()]);
    }

    public function newsarticle($slug)
    {
        $article = DB::table('blogs')->where('slug', $slug)->where('status', 1)->first();
        abort_if(!$article, 404);
        return view('newsarticle', compact('article'));
    }

    public function studentHome() { return view('student_home', ['user' => $this->getScannedUser()]); }
    public function studentChat() { return view('student_chat', ['user' => $this->getScannedUser()]); }
    public function studentFindteacher() { return view('student_findteacher', ['user' => $this->getScannedUser()]); }

    public function studentQuiz()
    {
        $user = $this->getScannedUser();
        if (!$user) { return redirect()->route('login'); }
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
        if (Cache::has($answeredKey) || $attempts >= 3) {
            $scoreboard = DB::table('users')->where('role', 'student')->where('klas_id', $user->klas_id)->orderByDesc('quiz_points')->limit(10)->get();
            return view('student_quiz', ['answered' => true, 'user' => $user, 'question' => null, 'scoreboard' => $scoreboard]);
        }
        $grade = (int) preg_replace('/[^0-9]/', '', ($user->klas_name ?? '0'));
        $dailyQuestionKey = 'daily_question_id_grade_' . $grade . '_' . $today;
        $questionId = Cache::remember($dailyQuestionKey, now()->endOfDay(), function () use ($grade) {
            return DB::table('questions_new')->where('klas', $grade)->inRandomOrder()->value('id');
        });
        $question = $questionId ? DB::table('questions_new')->where('id', $questionId)->first() : null;
        return view('student_quiz', ['question' => $question, 'user' => $user, 'answered' => false, 'attemptsLeft' => 3 - $attempts]);
    }

    public function submitQuizAnswer(Request $req)
    {
        $u = $this->getScannedUser();
        $q = DB::table('questions_new')->where('id', $req->input('question_id'))->first();
        if (!$u || !$q) return response()->json(['ok' => false]);
        $qText = $q->question ?? $q->text ?? '';
        $prompt = "Въпрос: \"{$qText}\". Ученикът отговаря: \"{$req->input('answer')}\". Ти си учител. Ако отговорът е правилен, напиши 'YES'. Ако е грешен, напиши 'NO' и дай подсказка.";
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
            if ($attempts == 1) { Cache::put($attemptsKey, 1, now()->endOfDay()); }
            return response()->json(['ok' => true, 'correct' => false, 'hint' => trim(str_replace(['NO', 'no'], '', $ai)), 'out_of_attempts' => $attempts >= 3]);
        } catch (\Throwable $e) { return response()->json(['ok' => false, 'error' => $e->getMessage()]); }
    }

    public function studentLostthings()
    {
        $items = DB::getSchemaBuilder()->hasTable('lost_items') ? DB::table('lost_items')->orderBy('created_at', 'desc')->get() : [];
        return view('student_lostthings', ['items' => $items, 'user' => $this->getScannedUser()]);
    }

    public function parentHome() { return view('parent_home'); }
    public function parentEvents() { return view('parent_events'); }
    public function parentClassroom() { return view('parent_classroom'); }
    public function parentRoute() { return view('parent_route'); }
    public function askAi(Request $request) { return response()->json($this->generateAiAnswer((string) $request->input('message', ''))); }

    public function startRemoteStt(Request $request)
    {
        $sessionId = (string) Str::uuid();
        $mode = (string) ($request->input('mode') ?? 'dictation');
        $this->sttStorePut($sessionId, ['status' => 'pending', 'session_id' => $sessionId, 'mode' => $mode]);
        try {
            $this->mqttPublish(env('MQTT_STT_TOPIC', 'znaibot/stt/listen'), json_encode(['session_id' => $sessionId, 'callback_url' => rtrim((string) $request->getSchemeAndHttpHost(), '/') . '/api/stt/ingest', 'callback_token' => env('STT_SHARED_TOKEN', 'change-me'), 'mode' => $mode], JSON_UNESCAPED_UNICODE));
            return response()->json(['ok' => true, 'session_id' => $sessionId, 'mode' => $mode]);
        } catch (\Throwable $e) { return response()->json(['ok' => false, 'message' => $e->getMessage()], 502); }
    }

    public function ingestSttResult(Request $request)
    {
        $sessionId = (string) $request->input('session_id');
        $transcript = trim((string) $request->input('text', $request->input('transcript', '')));
        $existing = $this->sttStoreGet($sessionId) ?? [];
        $mode = (string) ($existing['mode'] ?? 'dictation');
        $result = ($mode === 'conversation') ? $this->generateAiAnswer($transcript) : ['answer' => null, 'tts_url' => null, 'ok' => true];
        $payload = ['status' => 'done', 'session_id' => $sessionId, 'mode' => $mode, 'transcript' => $transcript, 'text' => $transcript, 'answer' => $result['answer'], 'tts_url' => $result['tts_url'] ?? null, 'ok' => $result['ok'] ?? false];
        $this->sttStorePut($sessionId, $payload);
        return response()->json($payload);
    }

    public function getSttResult(string $sessionId)
    {
        $payload = $this->sttStoreGet($sessionId);
        if (!$payload) { return response()->json(['status' => 'expired', 'session_id' => $sessionId], 404); }
        return response()->json($payload)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function getSttResultApi(string $sessionId) { return $this->getSttResult($sessionId); }

    public function proxyTts(Request $request)
    {
        $payload = ['text' => (string) $request->input('text'), 'voice' => (string) ($request->input('voice') ?? env('TTS_VOICE', 'bg-BG-BorislavNeural')), 'token' => env('TTS_TOKEN', ''), 'requested_at' => now()->toIso8601String()];
        try {
            $this->mqttPublish(env('MQTT_TTS_TOPIC', 'znaibot/tts/play'), json_encode($payload, JSON_UNESCAPED_UNICODE));
            return response()->json(['ok' => true, 'status' => 'queued']);
        } catch (\Throwable $e) { return response()->json(['ok' => false, 'message' => $e->getMessage()], 502); }
    }
}