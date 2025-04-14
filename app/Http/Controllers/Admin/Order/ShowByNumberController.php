<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Filters\Order\OrderFilter;
use App\Http\Requests\Admin\Order\ShowRequest;
use App\Models\Order;

class ShowByNumberController extends Controller
{
    public function __invoke(ShowRequest $request)
    {
        $data = $request->validated();

        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data, fn ($value) => $value !== null && $value !== '')]);

        $order = Order::filter($filter)->get();

        return response()->json($order);
    }
}
