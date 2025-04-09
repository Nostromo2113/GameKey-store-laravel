<?php

namespace App\Services\Admin\Order\OrderProduct;

use App\Models\Order;
use App\Services\Admin\Order\OrderActivationKey\OrderActivationKeyManager;
use Illuminate\Support\Collection;

class OrderProductCreateService
{
    private $activationKeyManager;

    public function __construct(OrderActivationKeyManager $activationKeyManager)
    {
        $this->activationKeyManager = $activationKeyManager;
    }

    /**
     * Добавляет продукты в заказ.
     *
     * @param array $requestOrderProducts - данные из запроса
     * @param Collection|null $selectedActivationKeys - ключи, выбранные для работы с заказом
     * @param Collection $existingOrderProducts - продукты, уже присутствующие в заказе
     * @param Order $order - объект заказа
     * @return void
     */
    public function addProductToOrder(
        array      $requestOrderProducts,
        Collection $selectedActivationKeys,
        Collection $existingOrderProducts,
        Order      $order
    ): void
    {
        if (empty($requestOrderProducts)) {
            return;
        }
        $existingOrderProductsByProductId = $existingOrderProducts->keyBy('product_id');

        // Сюда добавляем id продуктов, которые присутствуют в реквесте, но отсутствуют в заказе
        $productsToAdd = array_column(array_filter($requestOrderProducts, function ($requestOrderProduct) use ($existingOrderProductsByProductId) {
            return !$existingOrderProductsByProductId->has($requestOrderProduct['id']);
        }), 'id');

        // Преобразуем массив для массовой записи
        $productsToAdd = array_map(function ($productId) {
            return ['product_id' => $productId];
        }, $productsToAdd);

        // Добавляем продукты в заказ
        $addedOrderProducts = $order->orderProducts()->createMany($productsToAdd);
        $addedOrderProducts->load('activationKeys', 'product');
        // Массив для добавления ключей активации
        $activationKeysToAdd = [];
        // Связываем ключи активации с добавленными продуктами
        foreach ($requestOrderProducts as $requestOrderProduct) {
            $orderProduct = $addedOrderProducts->where('product_id', $requestOrderProduct['id'])->first();
            if ($orderProduct) {
                $activationKeysToAdd = array_merge($activationKeysToAdd, $this->activationKeyManager->prepareKeysForBinding($orderProduct, $requestOrderProduct, $selectedActivationKeys));
            }
        }

        // Привязываем ключи активации
        if (!empty($activationKeysToAdd)) {
            $this->activationKeyManager->bindKeys($activationKeysToAdd);
        }
    }
}
