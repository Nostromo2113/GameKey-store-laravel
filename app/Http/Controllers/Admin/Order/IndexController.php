<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Filters\Order\OrderFilter;
use App\Http\Requests\Admin\Order\FilterRequest;
use App\Models\Order;
use App\Services\Admin\Order\OrderService;

class IndexController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function __invoke(FilterRequest $filterRequest)
    {
        $data = $filterRequest->validated();
        $orderNumber = $data['order_number'] ?? null;

        if ($orderNumber) {

            $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data, fn($value) => $value !== null && $value !== '')]);

            $orderByOrderNumber = Order::filter($filter)->get();

            return response()->json($orderByOrderNumber);

        } else {
            return response()->json(Order::all());
        }
    }
}
