<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Order\{
    IndexController,
    StoreController,
    ShowController,
    UpdateController,
    DestroyController
};

use App\Http\Controllers\Admin\Order\OrderProduct\UpdateController as OrderProductUpdateController;


Route::group(['prefix' => 'orders'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{order}', ShowController::class);
    Route::patch('/{order}', UpdateController::class);
    Route::delete('/{order}', DestroyController::class);
    // OrderProduct
     Route::prefix('{order}/products')->group(function (){
        Route::patch('/', OrderProductUpdateController::class);
    });
});
