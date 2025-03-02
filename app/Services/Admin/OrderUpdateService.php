<?php

namespace App\Services\Admin;

use App\Managers\ActivationKeyManager;
use App\Managers\OrderProductManager;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
     * @param bool $isInTransaction Флаг для транзакций. По умолчанию false.
     * @return Order Возвращает обновленный заказ.
     * @throws \Exception
     */
    public function update(Order $order, array $data, bool $isInTransaction = false): Order
    {
        // Логика внутри callback, которая будет выполняться в транзакции
        $callback = function () use ($order, $data) {
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

            return $order;
        };

        // Управление обновлением заказа.
        // Проверка на статус и на выполнение транзакции
        if(!$order->isCompleted()) {
            if ($isInTransaction) {
                return $callback();
            } else {
                return DB::transaction($callback);
            }
        } else {
            throw new \Exception('Заказ уже завершен. Обновление невозможно', 403);
        }
    }
    /**
     * Переводит статус заказа в "Выполнено".
     *
     * @param Order $order Заказ для обновления.
     * @return Order Возвращает обновленный заказ.
     * @throws \Exception
     */
    public function executeOrder(Order $order) :Order
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
