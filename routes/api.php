<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;


RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60); // السماح بـ 60 طلب في الدقيقة
});


Route::get('/', function () {
    return response()->json(['message' => 'Welcome to API'], 200);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Sanctum
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/user', [UserController::class, 'profile']);

});
