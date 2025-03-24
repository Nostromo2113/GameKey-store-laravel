<?php

use Illuminate\Support\Facades\Route;

//  User
use App\Http\Controllers\Admin\User\{
    IndexController,
    StoreController,
    ShowController,
    UpdateController,
    DestroyController,
};

//  UserOrder
use App\Http\Controllers\Admin\User\UserOrder\{
    IndexController as UserOrderIndexController,
    StoreController as UserOrderStoreController,
    DestroyController as UserOrderDestroyController,
};

//  UserCart
use App\Http\Controllers\Admin\User\UserCart\{
    ShowController as UserCartShowController,
};


Route::group(['prefix' => 'users'], function () {
    //  User
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{user}', ShowController::class);
    Route::patch('/{user}', UpdateController::class);
    Route::delete('/{user}', DestroyController::class);
    //  UserOrder
    Route::prefix('{user}/orders')->group(function () {
        Route::get('/', UserOrderIndexController::class);
        Route::post('/', UserOrderStoreController::class);
        Route::delete('/{order}', UserOrderDestroyController::class);
    });
    //  UserCart
    Route::prefix('{user}/cart')->group(function () {
        Route::get('/', UserCartShowController::class);
    });
});
