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
    IndexController as ProductActivationKeyIndexController,
    StoreController as ProductActivationKeyStoreController,
};
// ProductComment
use App\Http\Controllers\Admin\Product\ProductComment\{
    IndexController as ProductCommentIndexController,
    StoreController as ProductCommentStoreController
};

Route::group(['prefix' => 'products'], function () {
    //  Product
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{product}', ShowController::class);
    Route::patch('/{product}', UpdateController::class);
    Route::delete('/{product}', DestroyController::class);
    //  ProductActivationKey
    Route::prefix('{product}/activation_keys')->group(function () {
        Route::get('/', ProductActivationKeyIndexController::class);
        Route::post('/', ProductActivationKeyStoreController::class);
    });
    Route::prefix('{product}/comments')->group(function () {
        Route::get('/', ProductCommentIndexController::class);
        Route::post('/', ProductCommentStoreController::class);
    });
});
