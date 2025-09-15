<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StatsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'admin'], function () {

    //  auth
    require base_path('routes/admin/auth.php');

    Route::middleware('auth:api')->group(function () {
        //  stats
        Route::get('stats', StatsController::class);
        //  categories
        require base_path('routes/admin/categories.php');
        //  genres
        require base_path('routes/admin/genres.php');
        //  users
        require base_path('routes/admin/users.php');
        //  products
        require base_path('routes/admin/products.php');
        //  comments
        require base_path('routes/admin/comments.php');
        //  activation keys
        require base_path('routes/admin/activationKeys.php');
        //  cart
        require base_path('routes/admin/cart.php');
        //  orders
        require base_path('routes/admin/orders.php');
    });

});

//  geoTracker
require  base_path('routes/tests/geoTracker.php');
