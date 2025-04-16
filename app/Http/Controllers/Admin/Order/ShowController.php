<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Order\OrderShowResource;
use App\Models\Order;

class ShowController extends Controller
{
    public function __invoke(Order $order)
    {
        $order->load([
            'user',
            'orderProducts.product',
            'orderProducts.activationKeys' => function ($query) {
                $query->withTrashed();
            }
        ]);

        return new OrderShowResource($order);
    }
}
