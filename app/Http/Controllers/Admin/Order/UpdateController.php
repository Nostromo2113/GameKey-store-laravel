<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Models\Order;
use App\Services\Admin\OrderService;

class UpdateController extends Controller
{
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }


    public function __invoke(Order $order, UpdateRequest $request)
    {
        $data = $request->validated();
        $this->orderService->update($order, $data);
    }
}
