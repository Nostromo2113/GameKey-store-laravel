<?php

namespace App\Managers;

use App\Models\Order;
use Illuminate\Support\Collection;

class OrderProductManager
{
    public function __construct(ActivationKeyManager $keyManager)
    {
        $this->keyManager = $keyManager;
    }

    /**
     * Добавляет новые продукты в заказ.
     *
     * @param array $requestOrderProducts - данные из запроса
     * @param Collection|null $selectedActivationKeys - ключи, выбранные для работы с заказом
     * @param Collection $existingOrderProducts - продукты, уже присутствующие в заказе
     * @param Order $order - объект заказа
     * @return void
     */
    public function addProductToOrder(
        array       $requestOrderProducts,
        Collection $selectedActivationKeys,
        Collection  $existingOrderProducts,
        Order       $order
    ): void
    {
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

        // Массив для добавления ключей активации
        $activationKeysToAdd = [];
        foreach ($requestOrderProducts as $requestOrderProduct) {
            $orderProduct = $addedOrderProducts->where('product_id', $requestOrderProduct['id'])->first();
            if ($orderProduct) {
                $activationKeysToAdd = array_merge($activationKeysToAdd, $this->keyManager->prepareKeysForBinding($orderProduct, $requestOrderProduct, $selectedActivationKeys));
            }
        }
        // Привязываем ключи активации
        if (!empty($activationKeysToAdd)) {
            $this->keyManager->bindKeys($activationKeysToAdd);
        }
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
        try {
            $order->orderProducts()
                ->whereIn('product_id', $orderProductIdsToRemove)
                ->delete();
            $this->keyManager->releaseKeys($orderProductIdsToRemove);
        } catch (\Exception $e) {
            throw new \Exception("Ошибка при удалении продуктов из заказа: " . $e->getMessage());
        }
    }



    /**
     * Обновляет существующие продукты в заказе или добавляет новые.
     *
     * @param array $requestOrderProducts - данные из запроса
     * @param Collection $filteredOrderProducts - продукты, уже присутствующие в заказе
     * @param Collection|null $selectedActivationKeys - ключи, выбранные для работы с заказом
     * @return void
     */
    public function updateProductQuantity(
        array       $requestOrderProducts,
        Collection  $filteredOrderProducts,
        ?Collection $selectedActivationKeys
    ): void
    {
        $filteredOrderProductsById = $filteredOrderProducts->keyBy('product_id');
        $activationKeysToUpdate = [];

        foreach ($requestOrderProducts as $requestItem) {
            $orderProduct = $filteredOrderProductsById->get($requestItem['id']);
            if ($orderProduct) {
                // Если продукт уже есть в заказе, формируем массив для обновления.
                $activationKeysToUpdate = array_merge($activationKeysToUpdate, $this->keyManager->prepareKeysForBinding($orderProduct, $requestItem, $selectedActivationKeys));
            }
        }

        if (!empty($activationKeysToUpdate)) {
            $this->keyManager->bindKeys($activationKeysToUpdate);
        }
    }
}
