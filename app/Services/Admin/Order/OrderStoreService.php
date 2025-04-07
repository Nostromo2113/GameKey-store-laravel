<?php

namespace App\Services\Admin\Order;

use App\Models\Order;
use App\Services\Admin\Cart\CartProduct\CartProductService;
use App\Services\Admin\Order\OrderProduct\OrderProductService;

class OrderStoreService
{
    public function storeOrder(array $data): Order
    {
        $userId = $data['user_id'];

        $order = Order::create([
            'user_id' => $userId,
            'status' => 'pending',
            'order_number' => $this->generateOrderNumber(5),
        ]);

        return $order;

    }
    private function generateOrderNumber(int $length = 5): int
    {
        do {
            $orderNumber = '';
            for ($i = 0; $i < $length; $i++) {
                $orderNumber .= random_int(0, 9);
            }
            $exists = Order::where('order_number', $orderNumber)->exists();
        } while ($exists);

        return $orderNumber;
    }
}
