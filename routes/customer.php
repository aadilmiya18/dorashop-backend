<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
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
