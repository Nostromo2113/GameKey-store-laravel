<?php

namespace App\Services\Admin\Order\OrderProduct;

use App\Models\Order;
use App\Services\Admin\Order\OrderActivationKey\OrderActivationKeyManager;

class OrderProductDeleteService
{
    private $activationKeyManager;

    public function __construct(OrderActivationKeyManager $activationKeyManager)
    {
        $this->activationKeyManager = $activationKeyManager;
    }

    /**
     * Удаляет записи продуктов из заказа.
     *
     * @param Order $order - текущий заказ
     * @param array $orderProductIdsToRemove - ID продуктов для удаления
     * @return void
     */
    public function removeProductFromOrder(Order $order, array $orderProductIdsToRemove): void
    {
        if (empty($orderProductIdsToRemove)) {
            return;
        }
            $order->orderProducts()
                ->whereIn('product_id', $orderProductIdsToRemove)
                ->delete();
            $this->activationKeyManager->releaseKeys($orderProductIdsToRemove);
    }
}
