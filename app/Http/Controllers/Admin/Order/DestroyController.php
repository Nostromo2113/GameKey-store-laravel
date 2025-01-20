<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DestroyController extends Controller
{
    public function __invoke($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json('Order removed', 200);
    }
}
