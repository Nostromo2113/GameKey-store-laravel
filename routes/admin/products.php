<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Product\IndexController;
use App\Http\Controllers\Admin\Product\StoreController;
use App\Http\Controllers\Admin\Product\ShowController;
use App\Http\Controllers\Admin\Product\UpdateController;
use App\Http\Controllers\Admin\Product\DestroyController;

// ProductActivationKey
use App\Http\Controllers\Admin\Product\ProductActivationKey\StoreController as ProductActivationKeyStoreController;
use App\Http\Controllers\Admin\Product\ProductActivationKey\DestroyController as ProductActivationKeyDestroyController;
use App\Http\Controllers\Admin\Product\ProductActivationKey\IndexController as ProductActivationKeyIndexController;


Route::group(['prefix' => 'products'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{product}', ShowController::class);
    Route::patch('/{product}', UpdateController::class);
    Route::delete('/{product}', DestroyController::class);

    Route::prefix('{product}/activation-keys')->group(function(){
        Route::get('/',  ProductActivationKeyIndexController::class);
        Route::post('/', ProductActivationKeyStoreController::class);
        Route::delete('/{activationKey}', ProductActivationKeyDestroyController::class);
    });
});
