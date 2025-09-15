<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeoPosition\{
    StoreController
};

Route::group(['prefix' => 'geoposition'], function () {
    Route::post('/', StoreController::class);
});
