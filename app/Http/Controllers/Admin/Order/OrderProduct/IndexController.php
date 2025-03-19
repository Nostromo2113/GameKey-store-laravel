<?php

namespace App\Http\Controllers\Admin\Order\OrderProduct;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Order\OrderShowResource;
use App\Models\Order;

class IndexController extends Controller
{
    public function __invoke(Order $order)
    {
        return New OrderShowResource($order);
    }
}
