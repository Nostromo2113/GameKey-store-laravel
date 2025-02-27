<?php

use App\Http\Controllers\Admin\Cart\IndexController;
use App\Http\Controllers\Admin\Cart\Products\StoreController;
use App\Http\Controllers\Admin\Cart\Products\UpdateController;
use App\Http\Controllers\Admin\Cart\Products\DestroyController;
use App\Http\Controllers\Admin\Cart\ShowController;
use Illuminate\Support\Facades\Route;

Route::prefix('cart')->group(function () {
    // Корзина
    Route::get('/', IndexController::class);
    Route::get('{cart}', ShowController::class);
    // Продукты корзины
    Route::prefix('{cart}/products')->group(function () {
        Route::post('/', StoreController::class);
        Route::patch('{product}', UpdateController::class);
        Route::delete('{product}', DestroyController::class);
    });
});
