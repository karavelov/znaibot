<?php

use App\Http\Controllers\Api\AiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\NfcLogIngestController;
use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\SchoolInfoController;
use App\Http\Controllers\Api\SecurityController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Backend\NfcLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// NFC четец endpoint (без auth — за хардуер в локална мрежа)
Route::post('/nfc/scan', [NfcLogController::class, 'scan'])->name('api.nfc.scan');

Route::prefix('v1')->middleware(['api.key', 'api.signature', 'api.fail-log'])->group(function () {
    Route::get('/', function () {
        return response()->json([
            'service' => 'znaibot-api',
            'version' => 'v1',
            'status' => 'ok',
        ]);
    });

    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/nfc/logs', [NfcLogIngestController::class, 'store']);

    Route::get('/school/info', [SchoolInfoController::class, 'index'])
        ->middleware('api.content-token');

    Route::get('/clubs', [SchoolInfoController::class, 'clubs'])
        ->middleware('api.content-token');

    Route::get('/news', [SchoolInfoController::class, 'news'])
        ->middleware('api.content-token');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::middleware('api.role:security,admin')->group(function () {
            Route::get('/security/lost-items', [SecurityController::class, 'lostItems']);
            Route::post('/security/lost-items', [SecurityController::class, 'storeLostItem']);
            Route::post('/security/lost-items/delete', [SecurityController::class, 'deleteLostItem']);
            Route::get('/security/entries', [SecurityController::class, 'entries']);
        });

        Route::middleware('api.role:parent,student,teacher,admin')->group(function () {
            Route::get('/teachers/map', [TeacherController::class, 'map']);
            Route::post('/schedule', [TeacherController::class, 'schedule']);
            Route::post('/parents/meetings', [ParentController::class, 'meetings']);
        });

        Route::middleware('api.role:parent,admin')->group(function () {
            Route::get('/parents/children', [ParentController::class, 'children']);
            Route::post('/parents/teachers', [ParentController::class, 'teachers']);
        });

        Route::middleware('api.role:teacher,admin')->group(function () {
            Route::get('/students', [TeacherController::class, 'students']);
            Route::post('/teacher/meetings/create', [TeacherController::class, 'createMeeting']);
        });

        Route::middleware('api.role:vendor,personal,admin')->group(function () {
            Route::get('/food/allergens', [FoodController::class, 'allergens']);
            Route::get('/food/students/allergens', [FoodController::class, 'studentsAllergens']);
            Route::post('/food/students/allergens/sync', [FoodController::class, 'syncStudentAllergens']);
        });

        Route::middleware('api.role:student,admin')->group(function () {
            Route::post('/ai/chat', [AiController::class, 'chat']);
        });
    });
});
