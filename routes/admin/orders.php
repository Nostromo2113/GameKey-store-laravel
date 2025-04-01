<?php

use Illuminate\Support\Facades\Route;

//  Order
use App\Http\Controllers\Admin\Order\{
    IndexController,
    ShowController,
    ShowByNumberController,
    UpdateController,
};
//  OrderProduct
use App\Http\Controllers\Admin\Order\OrderProduct\{
    BatchController as OrderProductUpdateController
};


Route::group(['prefix' => 'orders'], function () {
    //  Order
    Route::get('/', IndexController::class);
    Route::get('/by-number', ShowByNumberController::class);
    Route::get('/{order}', ShowController::class);
    Route::patch('/{order}', UpdateController::class);
    //  OrderProduct
     Route::prefix('{order}/products')->group(function (){
        Route::patch('/', OrderProductUpdateController::class);
    });
});
