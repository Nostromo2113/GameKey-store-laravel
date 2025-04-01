<?php

use App\Http\Controllers\Admin\ActivationKey\IndexController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'keys'], function () {
    Route::get('/', IndexController::class);
});
