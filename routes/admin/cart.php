<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Cart\IndexController;
use App\Http\Controllers\Admin\Cart\ShowController;

Route::group(['prefix' => 'cart'], function () {
    Route::get('/', IndexController::class);
    Route::get('/{user}', ShowController::class);
});
