<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Order\{
    IndexController,
    ShowController,
    ShowByNumberController,
    UpdateController,
};

use App\Http\Controllers\Admin\Order\OrderProduct\{
    UpdateController as OrderProductUpdateController
};


Route::group(['prefix' => 'orders'], function () {
    Route::get('/', IndexController::class);
    Route::get('/by-number', ShowByNumberController::class);
    Route::get('/{order}', ShowController::class);
    Route::patch('/{order}', UpdateController::class);


    // OrderProduct
     Route::prefix('{order}/products')->group(function (){
        Route::patch('/', OrderProductUpdateController::class);
    });
});
