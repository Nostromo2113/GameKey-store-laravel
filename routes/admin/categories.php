<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Category\IndexController;
use App\Http\Controllers\Admin\Category\StoreController;
use App\Http\Controllers\Admin\Category\ShowController;
use App\Http\Controllers\Admin\Category\UpdateController;
use App\Http\Controllers\Admin\Category\DestroyController;

Route::group(['prefix' => 'categories'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{category}', ShowController::class);
    Route::patch('/{category}', UpdateController::class);
    Route::delete('/{category}', DestroyController::class);
});
