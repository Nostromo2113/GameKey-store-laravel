<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Http\Resources\Admin\OrderProductResource;
use App\Models\Order;
use App\Services\Admin\Order\OrderService;

class UpdateController extends Controller
{
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }


    public function __invoke(Order $order, UpdateRequest $request)
    {
        $data = $request->validated();

        $order = $this->orderService->update($order, $data);
        $order->load('orderProducts', 'orderProducts.activationKeys');

        return response()->json([
            'data' => new OrderProductResource($order),
            'message' => 'Статус заказа успешно обновлен'
        ]);
    }
}
