<?php

namespace App\Repositories;

use App\Models\ActivationKey;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ActivationKeyRepository
{

    /**
     * Выбирает ключи активации для работы с заказом (привязка или удаление).
     *
     * @param array $requestOrderProducts - данные из запроса
     * @param Collection $filteredOrderProducts - продукты, уже присутствующие в заказе
     * @return Collection|null - отобранные ключи активации или null, если подходящих ключей нет
     */
    public function selectKeys(array $requestOrderProducts, Collection $products, Collection $existingOrderProducts): ?Collection
    {
        try {
            $bindings = [];
            $sqlParts = [];

            foreach ($requestOrderProducts as $requestOrderProduct) {
                $existingOrderProduct = $existingOrderProducts->firstWhere('product_id', $requestOrderProduct['id']);
                $orderProduct = $products->firstWhere('id', $requestOrderProduct['id']);
                $requestQuantity = (int)$requestOrderProduct['quantity'];
                if ($existingOrderProduct) {
                    $currentQuantity = $existingOrderProduct ? (int)$existingOrderProduct->activationKeys->count() : 0;
                    $calcQuantity = $requestQuantity - $currentQuantity;
                } else if ($orderProduct) {
                    $calcQuantity = $requestQuantity;
                }
                if ($calcQuantity !== 0) {
                    $condition = $calcQuantity > 0
                        ? "order_product_id IS NULL"
                        : "order_product_id = ?";
                    $sqlParts[] = "(SELECT * FROM activation_keys WHERE product_id = ? AND deleted_at IS NULL AND $condition LIMIT ?)";
                    $bindings[] = $requestOrderProduct['id'];
                    if ($calcQuantity < 0) {
                        $bindings[] = $orderProduct->id;
                    }
                    $bindings[] = abs($calcQuantity);
                }
            }

            if (!empty($sqlParts)) {
                $sql = implode(' UNION ALL ', $sqlParts);
                $activationKeys = DB::select($sql, $bindings);
                return ActivationKey::hydrate($activationKeys);
            }

            return null;
        } catch (\Exception $e) {
            throw new \Exception("Ошибка при выборе ключей активации.");
        }
    }


    /**
     * Массово обновляет ключи активации в базе данных.
     *
     * @param array $data - массив данных с обновляемыми ключами
     * @return void
     */
    public function bindKeys(array $data): void
    {
        if (empty($data)) {
            return;
        }

        try {
            $keyIds = [];
            $caseSql = 'CASE `id`';

            $bindings = [];

            foreach ($data as $group) {
                foreach ($group as $item) {
                    $keyIds[] = $item['activation_key_id'];
                    $orderProductId = $item['order_product_id'] ?? null;

                    $caseSql .= " WHEN ? THEN ?";

                    $bindings[] = $item['activation_key_id'];
                    $bindings[] = $orderProductId;
                }
            }

            $caseSql .= ' END';
            $keyIdsList = implode(',', array_fill(0, count($keyIds), '?')); // Используем плейсхолдеры для ID

            $bindings = array_merge($bindings, $keyIds);

            $sql = "
                UPDATE `activation_keys`
                SET `order_product_id` = {$caseSql}
                WHERE `id` IN ({$keyIdsList});
                    ";

            DB::statement($sql, $bindings);
        } catch (\Exception $e) {
            throw new \Exception("Ошибка при обновлении привязки ключей активации: " . $e->getMessage());
        }
    }



//
//    /**
//     * Освобождает ключи продуктов, удаляемых из заказа.
//     *
//     * @param array $orderProductIdsToRemove - ID продуктов для удаления
//     * @return void
//     */
//    public function releaseKeys(array $orderProductIdsToRemove): void
//    {
//        try {
//            ActivationKey::whereIn('order_product_id', $orderProductIdsToRemove)
//                ->update(['order_product_id' => null]);
//        } catch (\Exception $e) {
//            throw new \Exception("Ошибка при освобождении ключей активации: " . $e->getMessage());
//        }
//    }
//
//    /**
//     * Мягкое удаление ключей при завершеннии заказа.
//     *
//     * @param array $orderProductIdsToRemove - ID продуктов для удаления
//     * @return void
//     */
//    public function softDeleteKeys($orderProductIds): void
//    {
//        try {
//            ActivationKey::whereIn('order_product_id', $orderProductIds)->delete();
//        } catch (\Exception $e) {
//            throw new \Exception('Ошибка при мягком удалении ключей активации: ' . $e->getMessage());
//        }
//    }
//
//
//    /**
//     * Подготавливает ключи активации для привязки к заказу.
//     *
//     * @param mixed $orderProduct - продукт заказа
//     * @param array $requestItem - данные из запроса (количество и ID продукта)
//     * @param Collection|null $selectedActivationKeys - доступные ключи активации для привязки
//     * @return array - массив обновленных ключей
//     * @throws \Exception - если недостаточно ключей
//     */
//    public function prepareKeysForBinding($orderProduct, array $requestItem, ?Collection $selectedActivationKeys): array
//    {
//        try {
//            $currentActivationKeys = $orderProduct->activationKeys;
//            $product = $orderProduct->product;
//            $currentQuantity = $currentActivationKeys->count(); // Количество ключей в текущем заказе.
//            $requestedQuantity = $requestItem['quantity']; // Требуемое количество.
//            $activationKeysToUpdate = [];
//
//            if ($requestedQuantity > $currentQuantity) {
//                // Необходимы дополнительные ключи.
//                $additionalKeysNeeded = $requestedQuantity - $currentQuantity;
//                $availableKeys = $selectedActivationKeys->where('product_id', $product->id);
//                $availableKeysCount = $availableKeys->count();
//                if ($availableKeysCount < $additionalKeysNeeded) {
//                    throw new \Exception("Не хватает ключей активации. Нужно {$additionalKeysNeeded}, а доступно {$availableKeysCount}.");
//                } else {
//                    $activationKeysToUpdate[] = $this->prepareKeysForUpdate($availableKeys, $orderProduct);
//                }
//            } elseif ($requestedQuantity < $currentQuantity) {
//                // Нужно уменьшить количество привязанных ключей.
//                $keysToDetach = $currentActivationKeys->take($currentQuantity - $requestedQuantity);
//                $activationKeysToUpdate[] = $this->prepareKeysForUpdate($keysToDetach);
//            }
//            return $activationKeysToUpdate;
//        } catch (\Exception $e) {
//            throw new \Exception("Ошибка при подготовке ключей активации: " . $e->getMessage());
//        }
//    }
//
//
//    /**
//     * Привязывает и отвязывает ключи активации.
//     *
//     * @param Collection $keys - отобранные ключи активации
//     * @param mixed|null $orderProduct - модель из пивот-таблицы (если null, ключи отвязываются)
//     * @return array|null - массив обновленных данных для привязки ключей или null, если нет ключей
//     */
//    private function prepareKeysForUpdate(Collection $keys, $orderProduct = null): ?array
//    {
//        try {
//            if ($keys->isEmpty()) {
//                return null;
//            }
//
//            $orderProductId = $orderProduct ? $orderProduct->id : null;
//            $keyIds = $keys->pluck('id');
//            $keysToUpdate = [];
//
//            foreach ($keyIds as $keyId) {
//                $keysToUpdate[] = [
//                    'activation_key_id' => $keyId,
//                    'order_product_id' => $orderProductId
//                ];
//            }
//
//            return $keysToUpdate;
//        } catch (\Exception $e) {
//            throw new \Exception("Ошибка при привязке ключей активации: " . $e->getMessage());
//        }
//    }
}
