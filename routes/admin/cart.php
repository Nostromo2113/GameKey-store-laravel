<?php

use Illuminate\Support\Facades\Route;
//  Cart
use App\Http\Controllers\Admin\Cart\IndexController;
//CartProduct
use App\Http\Controllers\Admin\Cart\CartProduct\{
    StoreController,
    UpdateController,
    DestroyController
};

Route::group(['prefix' => 'cart'], function () {
    // Корзина
    Route::get('/', IndexController::class);

    // Продукты корзины
    Route::prefix('{cart}/products')->group(function () {
        Route::post('/', StoreController::class);
        Route::patch('{product}', UpdateController::class);
        Route::delete('{product}', DestroyController::class);
    });
});
