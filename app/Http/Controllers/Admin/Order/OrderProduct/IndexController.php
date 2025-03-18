<?php

namespace App\Http\Controllers\Admin\Order\OrderProduct;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderProductResource;
use App\Models\Order;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Order $order)
    {
        return New OrderProductResource($order);
    }
}
