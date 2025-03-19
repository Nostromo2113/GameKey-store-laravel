<?php

namespace App\Http\Controllers\Admin\Order\OrderProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\OrderProduct\UpdateRequest;
use App\Models\Order;
use App\Services\Admin\Order\OrderProduct\OrderProductService;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    private $orderProductService;
    public function __construct(OrderProductService $orderProductService)
    {
        $this->orderProductService = $orderProductService;
    }


    public function __invoke(Order $order, UpdateRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (!isset($data['order_products'])) {
            return response()->json([
                'message' => 'Данные о продуктах заказа отсутствуют.',
            ], 400);
        }

        $updatedOrder = $this->orderProductService->update($order, $data);

        return response()->json([
            'message' => 'Заказ успешно обновлен.',
            'order' => new \App\Http\Resources\Admin\Order\OrderShowResource($updatedOrder),
        ]);
    }
}
