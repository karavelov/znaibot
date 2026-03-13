<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ZnaiBotClientController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/card-scanned', function (Request $request) {
    $cardId = $request->input('card_id');

    if (!$cardId) {
        return response()->json(['success' => false, 'message' => 'No card ID provided'], 400);
    }

    $user = DB::table('users')
              ->where('nfc_id', $cardId)
              ->where('status', 'active')
              ->first();

    if ($user) {
        Cache::put('last_nfc_card_id', $user->nfc_id, now()->addSeconds(10));
        
        Cache::put('last_nfc_role', $user->role, now()->addSeconds(10));

        Log::info("NFC Scanned: Card {$cardId} belongs to {$user->name} ({$user->role})");

        return response()->json([
            'success' => true,
            'role' => $user->role,
            'name' => $user->name
        ]);
    } else {
        Log::warning("NFC Scanned: Card {$cardId} not found in database.");
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }
});

Route::post('/stt/ingest', [ZnaiBotClientController::class, 'ingestSttResult'])->name('stt.ingest');
Route::get('/stt/result/{sessionId}', [ZnaiBotClientController::class, 'getSttResultApi'])->name('stt.result.api');

Route::post('/user-facts', function (Request $request) {
    // DEBUG: Записваме, че е дошла заявка
    \Log::info('------------------------------------------');
    \Log::info('API HIT: /user-facts');
    
    // DEBUG: Какви данни получихме?
    \Log::info('INCOMING DATA:', $request->all());
    
    // DEBUG: Проверка на токена
    if ($request->bearerToken() !== 'a90u8fshd098ngophoisdfgysd09-hudfssd708g9yosdiugdsf') {
        \Log::error('ERROR: Invalid Token. Received: ' . $request->bearerToken());
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Валидация
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'text' => 'required|string',
    ]);

    if ($validator->fails()) {
        \Log::error('ERROR: Validation failed', $validator->errors()->toArray());
        return response()->json(['error' => $validator->errors()], 422);
    }

    // DEBUG: Търсене в базата
    \Log::info('SEARCHING USER: ' . $request->name);
    
    // Търсим по име (Exact Match)
    $user = DB::table('users')->where('name', $request->name)->first();

    if (!$user) {
        \Log::error('ERROR: User NOT found in DB.');
        
        // DEBUG: Нека видим какви имена има в базата, за да сравним
        $similarUsers = DB::table('users')
            ->where('name', 'LIKE', '%' . explode(' ', $request->name)[0] . '%')
            ->get(['id', 'name']);
            
        \Log::info('Similar users found in DB:', $similarUsers->toArray());
        
        return response()->json(['error' => 'User not found'], 404);
    }

    \Log::info('SUCCESS: User Found. ID: ' . $user->id);

    // Опит за запис
    try {
        DB::table('user_facts')->insert([
            'userid' => $user->id,
            'text' => $request->text,
        ]);
        \Log::info('SUCCESS: Fact inserted into DB.');
    } catch (\Exception $e) {
        \Log::error('DB ERROR: ' . $e->getMessage());
        return response()->json(['error' => 'Database error'], 500);
    }

    return response()->json(['success' => true]);
});
