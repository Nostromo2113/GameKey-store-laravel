<?php

namespace App\Services\Admin\Order\OrderActivationKey;

use App\Contracts\ActivationKeyRepositoryInterface;
use App\Models\ActivationKey;
use Illuminate\Support\Collection;

class OrderActivationKeyManager
{
    private $activationKeyRepository;

    public function __construct(ActivationKeyRepositoryInterface $activationKeyRepository)
    {
        $this->activationKeyRepository = $activationKeyRepository;
    }

    /**
     * Выбирает ключи активации для работы с заказом (привязка или удаление).
     * Обращается в репозиторий за сырым запросом
     *
     * @param array $requestOrderProducts - данные из запроса
     * @param Collection $requestProducts - продукты из запроса
     * @param Collection $existingProducts - текущее состояние заказа. Его продукты
     * @return Collection|null - отобранные ключи активации или null, если подходящих ключей нет
     */
    public function selectKeys(array $requestOrderProducts, Collection $requestProducts, Collection $existingProducts): ?Collection
    {
        return $this->activationKeyRepository->selectKeys($requestOrderProducts, $requestProducts, $existingProducts);
    }


    /**
     * Массово обновляет ключи активации в базе данных.
     * Обращается в репозиторий за сырым запросом
     *
     * @param array $data - массив данных с обновляемыми ключами
     * @return void
     */
    public function bindKeys(array $data): void
    {
        $this->activationKeyRepository->bindKeys($data);
    }


    /**
     * Освобождает ключи продуктов, удаляемых из заказа.
     *
     * @param array $orderProductIdsToRemove - ID продуктов для удаления
     * @return void
     */
    public function releaseKeys(array $orderProductIdsToRemove): void
    {
            ActivationKey::whereIn('order_product_id', $orderProductIdsToRemove)
                ->update(['order_product_id' => null]);
    }

    /**
     * Мягкое удаление ключей при завершеннии заказа.
     *
     * @param array $orderProductIds- ID продуктов для удаления
     * @return void
     */
    public function softDeleteKeys(array $orderProductIds): void
    {
            ActivationKey::whereIn('order_product_id', $orderProductIds)->delete();
    }


    /**
     * Мягкое удаление ключей при завершеннии заказа.
     *
     * @param array $orderProductIds- ID продуктов для получение ключей
     * @return array
     */
    public function returnOrderProductsKeys(array $orderProductIds): array
    {
            $keys = ActivationKey::whereIn('order_product_id', $orderProductIds)
                ->get()
                ->pluck('key')
                ->toArray();

            return $keys;
    }


    /**
     * Подготавливает ключи активации для привязки к заказу.
     *
     * @param mixed $orderProduct - продукт заказа
     * @param array $requestItem - данные из запроса (количество и ID продукта)
     * @param Collection|null $selectedActivationKeys - доступные ключи активации для привязки
     * @return array - массив обновленных ключей
     * @throws \Exception - если недостаточно ключей
     */
    public function prepareKeysForBinding($orderProduct, array $requestItem, ?Collection $selectedActivationKeys): array
    {
            $currentActivationKeys  = $orderProduct->activationKeys;
            $product                = $orderProduct->product;
            $currentQuantity        = $currentActivationKeys->count(); // Количество ключей в текущем заказе.
            $requestedQuantity      = $requestItem['quantity']; // Требуемое количество.
            $activationKeysToUpdate = [];

            if ($requestedQuantity > $currentQuantity) {
                // Необходимы дополнительные ключи.
                $additionalKeysNeeded = $requestedQuantity - $currentQuantity;
                $availableKeys        = $selectedActivationKeys->where('product_id', $product->id);
                $availableKeysCount   = $availableKeys->count();
                if ($availableKeysCount < $additionalKeysNeeded) {
                    throw new \Exception("Недостаточно ключей активации. Нужно {$additionalKeysNeeded}, а доступно {$availableKeysCount}.");
                } else {
                    $activationKeysToUpdate[] = $this->prepareKeysForUpdate($availableKeys, $orderProduct);
                }
            } elseif ($requestedQuantity < $currentQuantity) {
                // Нужно уменьшить количество привязанных ключей.
                $keysToDetach             = $currentActivationKeys->take($currentQuantity - $requestedQuantity);
                $activationKeysToUpdate[] = $this->prepareKeysForUpdate($keysToDetach);
            }

            return $activationKeysToUpdate;
    }


    /**
     * Привязывает и отвязывает ключи активации.
     *
     * @param Collection $keys - отобранные ключи активации
     * @param mixed|null $orderProduct - модель из пивот-таблицы (если null, ключи отвязываются)
     * @return array|null - массив обновленных данных для привязки ключей или null, если нет ключей
     */
    private function prepareKeysForUpdate(Collection $keys, $orderProduct = null): ?array
    {
            if ($keys->isEmpty()) {
                return null;
            }

            $orderProductId = $orderProduct ? $orderProduct->id : null;
            $keyIds         = $keys->pluck('id');
            $keysToUpdate   = [];

            foreach ($keyIds as $keyId) {
                $keysToUpdate[] = [
                    'activation_key_id' => $keyId,
                    'order_product_id'  => $orderProductId
                ];
            }

            return $keysToUpdate;
    }
}
