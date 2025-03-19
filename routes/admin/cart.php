<?php

use App\Http\Controllers\Admin\Cart\IndexController;
use App\Http\Controllers\Admin\Cart\CartProduct\StoreController;
use App\Http\Controllers\Admin\Cart\CartProduct\UpdateController;
use App\Http\Controllers\Admin\Cart\CartProduct\DestroyController;
use Illuminate\Support\Facades\Route;

Route::prefix('cart')->group(function () {
    // Корзина
    Route::get('/', IndexController::class);
    // Продукты корзины
    Route::prefix('{cart}/products')->group(function () {
        Route::post('/', StoreController::class);
        Route::patch('{product}', UpdateController::class);
        Route::delete('{product}', DestroyController::class);
    });
});
