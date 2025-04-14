<?php

namespace App\Http\Controllers\Admin\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;

class IndexController extends Controller
{
    public function __invoke()
    {
        $carts = Cart::all();

        return response()->json($carts, 200);
    }
}
