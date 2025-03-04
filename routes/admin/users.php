<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\User\IndexController;
use App\Http\Controllers\Admin\User\StoreController;
use App\Http\Controllers\Admin\User\ShowController;
use App\Http\Controllers\Admin\User\UpdateController;
use App\Http\Controllers\Admin\User\DestroyController;

Route::group(['prefix' => 'users'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{user}', ShowController::class);
    Route::patch('/{user}', UpdateController::class);
    Route::delete('/{user}', DestroyController::class);
});
