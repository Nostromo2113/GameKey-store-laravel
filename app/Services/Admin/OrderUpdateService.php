<?php

namespace App\Services\Admin;

use App\Managers\ActivationKeyManager;
use App\Managers\OrderProductManager;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

class OrderUpdateService
{
    private ActivationKeyManager $keyManager;
    private OrderProductManager $orderProductManager;

    public function __construct(ActivationKeyManager $keyManager, OrderProductManager $orderProductManager)
    {
        $this->keyManager = $keyManager;
        $this->orderProductManager = $orderProductManager;
    }

    /**
     * Выполняет обновление заказа. Управляет ключами привязанными к продукту в заказе.
     *
     * @param Order $order Заказ для обновления.
     * @param array $data Данные для обновления.
     * @return bool Возвращает true при успешном обновлении.
     * @throws \Exception
     */
    public function update(Order $order, array $data): bool
    {

        $requestedProducts = $data['order_products'];
        $requestedProductIds = array_column($requestedProducts, 'id');

        $existingProducts = $order->orderProducts()->whereIn('product_id', $requestedProductIds)->get();

        $products = Product::whereIn('id', $requestedProductIds)->get();

        $selectedActivationKeys = $this->keyManager->selectKeys($requestedProducts, $products, $existingProducts) ?? collect([]);

        $productsIds = $order->orderProducts()->pluck('product_id')->toArray();
        $productsToRemoveIds = array_diff($productsIds, $requestedProductIds);


        $this->performAddProductToOrder($requestedProducts, $selectedActivationKeys, $existingProducts, $order);

        $this->performUpdateProductQuantity($requestedProducts, $existingProducts, $selectedActivationKeys);
        $this->performRemoveProductToOrder($order, $productsToRemoveIds);

        return true;
    }

    /**
     * Добавляет продукты в заказ.
     *
     * @param array $requestedProducts Продукты, которые нужно добавить.
     * @param Collection $selectedActivationKeys Выбранные ключи активации.
     * @param Collection $existingProducts Уже существующие продукты в заказе.
     * @param Order $order Объект заказа.
     * @return void
     */
    private function performAddProductToOrder(array $requestedProducts, Collection $selectedActivationKeys, Collection $existingProducts, Order $order): void
    {

        if ($requestedProducts) {

            $this->orderProductManager->addProductToOrder($requestedProducts, $selectedActivationKeys, $existingProducts, $order);
        }
    }

    /**
     * Обновляет количество продуктов в заказе.
     *
     * @param array $requestedProducts Продукты, которые нужно обновить.
     * @param Collection $existingProducts Уже существующие продукты в заказе.
     * @param Collection $selectedActivationKeys Выбранные ключи активации.
     * @return void
     */
    private function performUpdateProductQuantity(array $requestedProducts, Collection $existingProducts, Collection $selectedActivationKeys): void
    {
        if ($requestedProducts && $existingProducts && $selectedActivationKeys) {
            $this->orderProductManager->updateProductQuantity($requestedProducts, $existingProducts, $selectedActivationKeys);
        }
    }

    /**
     * Удаляет продукты из заказа.
     *
     * @param Order $order Объект заказа.
     * @param array<int, int> $productsToRemoveIds ID продуктов, которые нужно удалить.
     * @return void
     */
    private function performRemoveProductToOrder(Order $order, array $productsToRemoveIds): void
    {
        if ($productsToRemoveIds) {
            $this->orderProductManager->removeProductFromOrder($order, $productsToRemoveIds);
        }
    }
}
