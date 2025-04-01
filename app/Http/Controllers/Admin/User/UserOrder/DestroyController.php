<?php

namespace App\Http\Controllers\Admin\User\UserOrder;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class DestroyController extends Controller
{
    public function __invoke(User $user, Order $order)
    {
        $order->delete();
        return response()->json('Order removed', 200);
    }
}
