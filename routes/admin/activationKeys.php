<?php

use App\Http\Controllers\Admin\ActivationKey\IndexController;
use App\Http\Controllers\Admin\ActivationKey\DestroyController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'activation_keys'], function () {
    Route::get('/', IndexController::class);
    Route::delete('/{key}', DestroyController::class);
});
