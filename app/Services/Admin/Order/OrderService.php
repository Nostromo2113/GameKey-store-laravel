<?php

namespace App\Services\Admin\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private $orderStoreService;
    private $orderUpdateService;

    public function __construct(
        OrderStoreService  $orderStoreService,
        OrderUpdateService $orderUpdateService
    ) {
        $this->orderStoreService  = $orderStoreService;
        $this->orderUpdateService = $orderUpdateService;
    }


    public function store(array $data): Order
    {
        // Единичное обращение к бд. Транзакция не нужна
        $order = $this->orderStoreService->storeOrder($data);

        return $order;
    }


    public function update(Order $order, array $data): Order
    {
        return DB::transaction(function () use ($order, $data) {
            $order = $this->orderUpdateService->update($order, $data);

            return $order;
        });
    }

}
