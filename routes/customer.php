<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::prefix('auth')->controller(CustomerAuthController::class)->group(function () {
    Route::post('register', 'registerUser');
    Route::post('login', 'loginUser');
});

Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('', 'onlyParents');
    Route::get('/{id}', 'show');
    Route::get('/{slug}/products', 'categoryProducts');
    Route::get('/{slug}/children', 'parentChildCategories');
});

Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::get('/{slug}', 'productDetailsBySlug');
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [CustomerAuthController::class, 'me']);
    Route::post('auth/logout', [CustomerAuthController::class, 'logoutUser']);

    Route::controller(CartController::class)->prefix('carts')->group(function () {
        Route::get('','index');
        Route::post('add','store');
        Route::delete('remove','destroy');
    });

});
