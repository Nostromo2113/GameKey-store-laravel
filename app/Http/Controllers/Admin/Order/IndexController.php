<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\IndexRequest;
use App\Models\Order;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $userId = $data['user_id'] ?? null;
        $orderNumber = $data['query'] ?? null;
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $orders = $user->orders;
            return response()->json($orders);
        } else if ($orderNumber) {
            $selectedOrder = Order::where('order_number', '=', $orderNumber)->get();
            if ($selectedOrder->isEmpty()) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            return response()->json($selectedOrder);
        } else {
            return response()->json(Order::all());
        }
    }
}
