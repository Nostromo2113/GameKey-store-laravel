<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UpdateRequest;
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
            $this->orderService->update($order, $data);
        } catch(\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
