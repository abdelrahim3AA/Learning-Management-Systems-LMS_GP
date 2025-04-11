<?php

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\LessonProgressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Http\Controllers\Api\AIChatLogController;

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to API'], 200);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Sanctum
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::apiResource('users', UserController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('teachers', TeacherController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('lessons', LessonController::class);
Route::apiResource('lesson-progress', LessonProgressController::class);
// AI Chat Log Routes
Route::middleware('auth:sanctum')->group(function () {
    // Get all chat logs
    Route::get('/chat-logs', [AIChatLogController::class, 'index']);

    // Send a message and get AI response
    Route::post('/chat-logs', [AIChatLogController::class, 'store']);

    // Get chat history for a specific user
    Route::get('/chat-logs/user/{userId}', [AIChatLogController::class, 'userHistory']);

    // Get a specific chat log
    Route::get('/chat-logs/{aiChatLog}', [AIChatLogController::class, 'show']);

    // Update a specific chat log
    Route::put('/chat-logs/{aiChatLog}', [AIChatLogController::class, 'update']);
    Route::patch('/chat-logs/{aiChatLog}', [AIChatLogController::class, 'update']);

    // Delete a specific chat log
    Route::delete('/chat-logs/{aiChatLog}', [AIChatLogController::class, 'destroy']);
});

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60);
});



