<?php

namespace App\Http\Controllers\Admin\User\UserOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserOrder\StoreRequest;
use App\Services\Admin\Cart\CartProduct\CartProductService;
use App\Services\Admin\Order\OrderProduct\OrderProductService;
use App\Services\Admin\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class StoreController extends Controller
{
    private $orderService;
    private $cartProductService;
    private $orderProductService;

    public function __construct(OrderService $orderService, CartProductService $cartProductService, OrderProductService $orderProductService)
    {
        $this->orderService = $orderService;
        $this->cartProductService = $cartProductService;
        $this->orderProductService = $orderProductService;
    }

    public function __invoke(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $order = $this->orderService->store($data['order']);

            if (auth()->id() === $data['order']['user_id']) {
                $this->cartProductService->destroyAll($data['order']['user_id']);
            }

            $this->orderProductService->batch($order, $data['order']);

            DB::commit();

            return response()->json([
                'message' => 'Заказ создан',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ошибка при создании заказа',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
