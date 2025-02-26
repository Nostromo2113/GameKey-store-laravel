<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Services\Admin\OrderUpdateService;

class UpdateController extends Controller
{
    public function __construct(OrderUpdateService $orderService)
    {
        $this->orderService = $orderService;
    }


    public function __invoke(Order $order, UpdateRequest $request)
    {
        $data = $request->validated();
        try {
           $updatedOrder = $this->orderService->update($order, $data);
           return response()->json(['message' => 'order has been updated', 'order' => new OrderResource($updatedOrder)]);
        } catch(\Exception $e) {
            return response()->json(['Ошибка при обновлении продукта в заказе' => $e->getMessage()], 500);
        }
    }
}
