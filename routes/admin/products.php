<?php

use Illuminate\Support\Facades\Route;

//  Product
use App\Http\Controllers\Admin\Product\{
  IndexController,
  StoreController,
  ShowController,
  UpdateController,
  DestroyController
};

// ProductActivationKey
use App\Http\Controllers\Admin\Product\ProductActivationKey\{
  StoreController as ProductActivationKeyIndexController,
  StoreController as ProductActivationKeyStoreController,
  DestroyController as ProductActivationKeyDestroyController
};


Route::group(['prefix' => 'products'], function () {
    //  Product
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{product}', ShowController::class);
    Route::patch('/{product}', UpdateController::class);
    Route::delete('/{product}', DestroyController::class);
    //  ProductActivationKey
    Route::prefix('{product}/activation-keys')->group(function(){
        Route::get('/',  ProductActivationKeyIndexController::class);
        Route::post('/', ProductActivationKeyStoreController::class);
        Route::delete('/{activationKey}', ProductActivationKeyDestroyController::class);
    });
});
