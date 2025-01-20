<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Genre\IndexController;
use App\Http\Controllers\Admin\Genre\StoreController;
use App\Http\Controllers\Admin\Genre\ShowController;
use App\Http\Controllers\Admin\Genre\UpdateController;
use App\Http\Controllers\Admin\Genre\DestroyController;

Route::group(['prefix' => 'genres'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{genre}', ShowController::class);
    Route::patch('/{genre}', UpdateController::class);
    Route::delete('/{genre}', DestroyController::class);
});
