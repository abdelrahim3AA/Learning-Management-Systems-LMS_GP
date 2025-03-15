<?php

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\UserController;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;


Route::apiResource('users', UserController::class);
Route::apiResource('students', StudentController::class);   

// RateLimiter::for('api', function (Request $request) {
//     return Limit::perMinute(60); // السماح بـ 60 طلب في الدقيقة
// });


// Route::get('/', function () {
//     return response()->json(['message' => 'Welcome to API'], 200);
// });


// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// // Sanctum
// Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

//     Route::post('/logout', [AuthController::class, 'logout']);
// });


