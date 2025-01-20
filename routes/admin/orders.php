<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Order\IndexController;
use App\Http\Controllers\Admin\Order\StoreController;
use App\Http\Controllers\Admin\Order\ShowController;
use App\Http\Controllers\Admin\Order\UpdateController;
use App\Http\Controllers\Admin\Order\DestroyController;

Route::group(['prefix' => 'orders'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{order}', ShowController::class);
    Route::patch('/{order}', UpdateController::class);
    Route::delete('/{order}', DestroyController::class);
});
