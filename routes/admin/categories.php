<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Category\{
  IndexController,
  StoreController,
  ShowController,
  UpdateController,
  DestroyController
};

Route::group(['prefix' => 'categories'], function () {
    Route::get('/', IndexController::class);
    Route::post('/', StoreController::class);
    Route::get('/{category}', ShowController::class);
    Route::patch('/{category}', UpdateController::class);
    Route::delete('/{category}', DestroyController::class);
});
