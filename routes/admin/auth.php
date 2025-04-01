<?php

use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\Auth\PasswordUpdateController;
use App\Http\Controllers\Admin\Auth\RegistrationController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['prefix' => 'password'], function() {
    Route::post('reset', PasswordResetController::class);
    Route::post('change', PasswordUpdateController::class)->middleware('auth:api');
});

Route::post('/registration', RegistrationController::class);
