<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\StoreRequest;
use App\Models\Order;


class StoreController extends Controller
{

    public function __invoke(StoreRequest $request)
    {
        $userId = $request->validated()['user_id'];

        $order = Order::create([
            'user_id' => $userId,
            'total_price' => 0,
            'status' => 'В работе',
            'order_number' => $this->generateOrderNumber(5),

        ]);
        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);

    }
    private function generateOrderNumber($length = 5)
    {
        do {
            // Генерация случайного номера заказа
            $orderNumber = '';
            for ($i = 0; $i < $length; $i++) {
                $orderNumber .= random_int(0, 9);
            }

            // Проверка на уникальность
            $exists = Order::where('order_number', $orderNumber)->exists();
        } while ($exists);  // Если номер существует, генерируем новый

        return $orderNumber;  // Возвращаем уникальный номер
    }
}
