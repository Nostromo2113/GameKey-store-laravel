<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Comment\IndexController;
use App\Http\Controllers\Admin\Comment\StoreController;
use App\Http\Controllers\Admin\Comment\ShowController;
use App\Http\Controllers\Admin\Comment\UpdateController;
use App\Http\Controllers\Admin\Comment\DestroyController;

Route::group(['prefix' => 'comments'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{comment}', ShowController::class);
    Route::patch('/{comment}', UpdateController::class);
    Route::delete('/{comment}', DestroyController::class);
});
