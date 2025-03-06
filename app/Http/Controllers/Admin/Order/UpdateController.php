<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Services\Admin\Order\OrderProduct\OrderProductService;

class UpdateController extends Controller
{
    private $orderProductService;
    public function __construct(OrderProductService $orderProductService)
    {
        $this->orderProductService = $orderProductService;
    }


    public function __invoke(Order $order, UpdateRequest $request)
    {
        $data = $request->validated();
            $executeOrder = $data['is_execute'] ?? false;
            if ($executeOrder) {
              $updatedOrder = $this->orderProductService->executeOrder($order);
            } else if(isset($data['order_products']) && !$executeOrder) {
                $updatedOrder = $this->orderProductService->update($order, $data);
            } else {
                return response()->json(['message'=> 'Полученных данных недостаточно'], 500);
            }
            return response()->json(['message' => '', 'order' => new OrderResource($updatedOrder)]);
    }
}
