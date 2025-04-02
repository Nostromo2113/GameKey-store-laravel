<?php

namespace App\Services\Admin\Order\OrderProduct;

use App\Models\Order;
use App\Models\Product;
use App\Services\Admin\Order\OrderActivationKey\OrderActivationKeyManager;
use Illuminate\Support\Facades\DB;

class OrderProductService
{
    private $keyManager;
    private $orderProductCreate;
    private $orderProductUpdate;
    private $orderProductDelete;

    public function __construct
    (
        OrderActivationKeyManager $orderActivationKeyManager,
        OrderProductCreateService $orderProductCreateService,
        OrderProductUpdateService $orderProductUpdateService,
        OrderProductDeleteService $orderProductDeleteService
    )
    {
        $this->keyManager = $orderActivationKeyManager;
        $this->orderProductCreate = $orderProductCreateService;
        $this->orderProductUpdate = $orderProductUpdateService;
        $this->orderProductDelete = $orderProductDeleteService;
    }


    /**
     * Выполняет обновление заказа. Управляет ключами привязанными к продукту в заказе.
     * @param Order $order Заказ для обновления
     * @param array $data Данные для обновления
     * @param Boolean $useTransaction Из-за отстутствия отдельных контроллеров для shop, приходится использовать флаг для транзакции
     * @return Order Возвращает обновленный заказ
     * @throws \Exception
     */
    public function batch(Order $order, array $data, bool $useTransaction = false): Order
    {
        if ($order->isCompleted()) {
            throw new \Exception('Заказ уже завершен. Обновление невозможно', 403);
        }

        if (!isset($data['order_products'])) {
            return $order;
        }

        $process = function () use ($order, $data) {
            $requestOrderProducts = $data['order_products'];
            $requestedProductIds = array_column($requestOrderProducts, 'id');

            $existingProducts = $order->orderProducts()
                ->with(['activationKeys', 'product'])
                ->whereIn('product_id', $requestedProductIds)
                ->get();
            $products = Product::whereIn('id', $requestedProductIds)->get();

            $selectedActivationKeys = $this->keyManager->selectKeys($requestOrderProducts, $products, $existingProducts) ?? collect([]);

            $productsIds = $order->orderProducts()->pluck('product_id')->toArray();
            $productsToRemoveIds = array_diff($productsIds, $requestedProductIds);

            $this->orderProductCreate->addProductToOrder($requestOrderProducts, $selectedActivationKeys, $existingProducts, $order);
            $this->orderProductUpdate->updateProductQuantity($requestOrderProducts, $existingProducts, $selectedActivationKeys);
            $this->orderProductDelete->removeProductFromOrder($order, $productsToRemoveIds);

            return $order;
        };

        return $useTransaction
            ? DB::transaction($process)
            : $process();
    }
}
