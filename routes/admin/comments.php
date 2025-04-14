<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Comment\{
    ShowController,
    UpdateController,
    DestroyController
};

Route::group(['prefix' => 'comments'], function () {
    Route::get('/{comment}', ShowController::class);
    Route::patch('/{comment}', UpdateController::class);
    Route::delete('/{comment}', DestroyController::class);
});
