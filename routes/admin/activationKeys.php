<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ActivationKey\StoreController;
use App\Http\Controllers\Admin\ActivationKey\DestroyController;
use App\Http\Controllers\Admin\ActivationKey\IndexController;

Route::group(['prefix' => 'keys'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::delete('/{key}', DestroyController::class);
});
