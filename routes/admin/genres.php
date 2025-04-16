<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Genre\{
    IndexController,
    StoreController,
    UpdateController,
    DestroyController
};

Route::group(['prefix' => 'genres'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::patch('/{genre}', UpdateController::class);
    Route::delete('/{genre}', DestroyController::class);
});
