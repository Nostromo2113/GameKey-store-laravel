<?php

namespace App\Services\Admin\Order;

use App\Models\Order;
use App\Services\Admin\Order\OrderActivationKey\OrderActivationKeyManager;

class OrderUpdateService
{
    private $keyManager;

    public function __construct(OrderActivationKeyManager $keyManager)
    {
        $this->keyManager = $keyManager;
    }



    /**
     * Выполняет необходимые обновления заказа. Без продуктов.
     *
     * @param Order $order Заказ для обновления.
     * @param array $data Данные для обновления.
     * @return Order Возвращает обновленный заказ.
     * @throws \Exception
     */
    public function update(Order $order, array $data): Order
    {
        if(isset($data['is_execute'])){
            $updatedOrder = $this->executeOrder($order);
            return $updatedOrder;
        } else {
            throw new \InvalidArgumentException('Данные для обновления заказа отсутствуют.', 500);
        }
    }



    /**
     * Переводит статус заказа в "Выполнено".
     *
     * @param Order $order Заказ для обновления.
     * @return Order Возвращает обновленный заказ.
     * @throws \Exception
     */
    private function executeOrder(Order $order) :Order
    {
        try {
            if($order->isCompleted()){
                throw new \Exception('Заказ выполнен. Изменение статуса невозможно', 403);
            }
            $order->status = 'completed';
            $order->save();
            $orderProductsIds = $order->orderProducts->pluck('id')->toArray();
            $this->keyManager->softDeleteKeys($orderProductsIds);
            return $order;
        } catch(\Exception $e) {
            throw new \Exception('Ошибка изменения статуса заказа: ' . $e->getMessage(), $e->getCode());
        }
    }
}
