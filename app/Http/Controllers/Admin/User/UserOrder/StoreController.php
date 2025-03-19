<?php

namespace App\Http\Controllers\Admin\User\UserOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserOrder\StoreRequest;
use App\Services\Admin\Order\OrderService;


class StoreController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $order = $this->orderService->store($data['order']);

        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);

    }
}
