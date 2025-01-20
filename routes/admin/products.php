<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Product\IndexController;
use App\Http\Controllers\Admin\Product\StoreController;
use App\Http\Controllers\Admin\Product\ShowController;
use App\Http\Controllers\Admin\Product\UpdateController;
use App\Http\Controllers\Admin\Product\DestroyController;

Route::group(['prefix' => 'products'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{product}', ShowController::class);
    Route::patch('/{product}', UpdateController::class);
    Route::delete('/{product}', DestroyController::class);
});
