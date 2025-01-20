<?php

use App\Http\Controllers\Admin\Auth\PasswordUpdateController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::group(['prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Admin'], function () {

    //categories
    require base_path('routes/admin/categories.php');
    //genres
    require base_path('routes/admin/genres.php');
    //users
    require base_path('routes/admin/users.php');
    //products
    require base_path('routes/admin/products.php');
    //comments
    require base_path('routes/admin/comments.php');
    //activation keys
    require base_path('routes/admin/activationKeys.php');
    //cart
    require base_path('routes/admin/cart.php');
    //orders
    require base_path('routes/admin/orders.php');

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
        Route::post('reset', [PasswordResetController::class, 'sendResetPasswordMail']);
        Route::post('change', [PasswordUpdateController::class, 'changePassword']);
    });

});


