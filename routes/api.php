<?php

use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'registerUser');
    Route::post('login', 'loginUser');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logoutUser']);

    Route::controller(BrandController::class)->prefix('brands')->group(function () {
        Route::get('','index');
        Route::post('','store');
        Route::get('/{id}','show');
        Route::put('/{id}','update');
        Route::delete('/{id}','destroy');
    });

});
