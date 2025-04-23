<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Query\Filter\OrderFilter;
use App\Models\Order;

class ShowByNumberController extends Controller
{
    public function __invoke($query)
    {
        $filter = app(OrderFilter::class, [
            'queryParams' => ['order_number' => $query]
        ]);

        $orders = Order::filter($filter)->get();

        return response()->json($orders);
    }
}
