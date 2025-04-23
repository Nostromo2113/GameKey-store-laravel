<?php

namespace App\Http\Controllers\Admin\User\UserOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserOrder\StoreRequest;
use App\Services\Admin\Cart\CartProduct\CartProductDestroyer;
use App\Services\Admin\Cart\CartProduct\CartProductService;
use App\Services\Admin\Order\OrderProduct\OrderProductBatch;
use App\Services\Admin\Order\OrderService;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    private $orderService;
    private $cartProductDestoyer;
    private $orderProductService;

    public function __construct(OrderService $orderService, CartProductDestroyer $cartProductDestroyer, OrderProductBatch $orderProductService)
    {
        $this->orderService        = $orderService;
        $this->cartProductDestoyer = $cartProductDestroyer;
        $this->orderProductService = $orderProductService;
    }

    public function __invoke(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $order = $this->orderService->store($data['order']);

            if (auth()->id() === $data['order']['user_id']) {
                $this->cartProductDestoyer->deleteAllProductsInCart($data['order']['user_id']);
            }

            $this->orderProductService->batch($order, $data['order']);

            DB::commit();

            return response()->json([
                'message' => 'Заказ создан',
                'data'    => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ошибка при создании заказа',
            ], 500);
        }
    }
}
