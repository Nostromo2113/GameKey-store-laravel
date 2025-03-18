<?php

namespace App\Services\Admin\Order;

use App\Models\Order;
use App\Services\Admin\Order\OrderActivationKey\OrderActivationKeyManager;


class OrderService
{
    private $orderStoreService;
    private $orderUpdateService;

    public function __construct
    (
        OrderStoreService $orderStoreService,
        OrderUpdateService $orderUpdateService
    )
    {
        $this->orderStoreService = $orderStoreService;
        $this->orderUpdateService = $orderUpdateService;
    }


    public function store(array $data): Order
    {
        $order = $this->orderStoreService->storeOrder($data);
        return $order;
    }


    public function update(Order $order, array $data): Order
    {
        $order = $this->orderUpdateService->update($order, $data);
        return $order;
    }

}
