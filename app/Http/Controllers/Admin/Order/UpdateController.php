<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Http\Resources\Admin\Order\OrderShowResource;
use App\Models\Order;
use App\Services\Admin\Order\OrderUpdateService;

class UpdateController extends Controller
{
    private $orderUpdateService;

    public function __construct(OrderUpdateService $orderUpdateService)
    {
        $this->orderUpdateService = $orderUpdateService;
    }

    public function __invoke(Order $order, UpdateRequest $request)
    {
        $data = $request->validated();
        $order = $this->orderUpdateService->update($order, $data);

        $order->load([
            'user',
            'orderProducts.product',
            'orderProducts.activationKeys' => function ($query) {
                $query->withTrashed();
            }
        ]);

        return response()->json([
            'message' => 'Статус заказа изменен',
            'data' => new OrderShowResource($order)
        ]);
    }
}
