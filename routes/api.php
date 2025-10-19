<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'registerUser');
    Route::post('login', 'loginUser');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logoutUser']);
});
