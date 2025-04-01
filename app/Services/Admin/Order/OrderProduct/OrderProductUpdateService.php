<?php

namespace App\Services\Admin\Order\OrderProduct;

use App\Services\Admin\Order\OrderActivationKey\OrderActivationKeyManager;
use Illuminate\Support\Collection;

class OrderProductUpdateService
{
    private $activationKeyManager;

    public function __construct(OrderActivationKeyManager $activationKeyManager)
    {
        $this->activationKeyManager = $activationKeyManager;
    }

    /**
     * Обновляет существующие продукты в заказе или добавляет новые.
     *
     * @param array $requestOrderProducts - данные из запроса
     * @param Collection $existingProducts - продукты, уже присутствующие в заказе
     * @param Collection|null $selectedActivationKeys - ключи, выбранные для работы с заказом
     * @return void
     */
    public function updateProductQuantity(
        array       $requestOrderProducts,
        Collection  $existingProducts,
        ?Collection $selectedActivationKeys
    ): void
    {
        if (empty($requestOrderProducts) && empty($existingProducts) && empty($selectedActivationKeys)) {
            return;
        }

        $existingProductsById = $existingProducts->keyBy('product_id');
        $activationKeysToUpdate = [];

        foreach ($requestOrderProducts as $requestItem) {
            $orderProduct = $existingProductsById->get($requestItem['id']);
            if ($orderProduct) {
                // Если продукт уже есть в заказе, формируем массив для обновления.
                $activationKeysToUpdate = array_merge($activationKeysToUpdate, $this->activationKeyManager->prepareKeysForBinding($orderProduct, $requestItem, $selectedActivationKeys));
            }
        }

        if (!empty($activationKeysToUpdate)) {
            $this->activationKeyManager->bindKeys($activationKeysToUpdate);
        }
    }
}


