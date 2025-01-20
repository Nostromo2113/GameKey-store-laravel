<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;

class ShowController extends Controller
{
    public function __invoke(Order $order)
    {
        return New OrderResource($order);
    }
}
