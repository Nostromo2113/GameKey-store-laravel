<?php

namespace App\Http\Controllers\Admin\Order\OrderProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\OrderProduct\UpdateRequest;
use App\Http\Resources\Admin\Order\OrderShowResource;
use App\Models\Order;
use App\Services\Admin\Order\OrderProduct\OrderProductBatch;
use Illuminate\Http\JsonResponse;

class BatchController extends Controller
{
    private $orderProductService;
    public function __construct(OrderProductBatch $orderProductService)
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

        $useTransaction = true; // Для читаемости

        $updatedOrder = $this->orderProductService->batch($order, $data, $useTransaction);

        $updatedOrder->load([
            'user',
            'orderProducts.product.category',
            'orderProducts.activationKeys'
        ]);

        return response()->json([
            'message' => 'Заказ успешно обновлен.',
            'order'   => new OrderShowResource($updatedOrder),
        ]);
    }
}
