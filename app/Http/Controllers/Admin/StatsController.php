<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ActivationKey;

class StatsController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'orders'          => Order::count(),
            'products'        => Product::count(),
            'users'           => User::count(),
            'activation_keys' => ActivationKey::count(),
        ]);
    }
}
