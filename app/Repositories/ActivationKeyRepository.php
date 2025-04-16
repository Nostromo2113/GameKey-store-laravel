<?php

namespace App\Repositories;

use App\Contracts\ActivationKeyRepositoryInterface;
use App\Models\ActivationKey;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ActivationKeyRepository implements ActivationKeyRepositoryInterface
{
    /**
     * Ниже будет сырой SQL с использованием union. Причины выбора такого подхода:
     *  1. Была задача извлечь ровно то количество ключей из базы, какое нам требуется для batch запроса.
     *      Ключи извлекаются для разных продуктов, в разном количестве, для добавления, открепления, прикрепления к продукту в заказе.
     *  2. Вариант извлечь сразу все ключи и работать с ними на уровне PHP меня не устроил
     *  3. Выполнять запрос в цикле n+1
     *  4. До оконных функций SQL на данном этапе написания кода еще не добрался
     *
     *  Класс реализован в сервисе через Dependency Injection, и в любой момент может быть безболезненно заменен.
     */


    /**
     * Выбирает ключи активации для работы с заказом (привязка и/или удаление).
     *
     * @param array $requestOrderProducts - данные из запроса
     * @param Collection $filteredOrderProducts - продукты, уже присутствующие в заказе
     * @return Collection|null - отобранные ключи активации или null, если подходящих ключей нет
     */
    public function selectKeys(array $requestOrderProducts, Collection $products, Collection $existingProducts): ?Collection
    {
        try {
            $bindings = [];
            $sqlParts = [];

            foreach ($requestOrderProducts as $requestOrderProduct) {
                $existingProduct = $existingProducts->firstWhere('product_id', $requestOrderProduct['id']);
                $orderProduct    = $products->firstWhere('id', $requestOrderProduct['id']);
                $requestQuantity = (int)$requestOrderProduct['quantity'];
                if ($existingProduct) {
                    $currentQuantity = $existingProduct ? (int)$existingProduct->activationKeys->count() : 0;
                    $calcQuantity    = $requestQuantity - $currentQuantity;
                } elseif ($orderProduct) {
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
                $sql            = implode(' UNION ALL ', $sqlParts);
                $activationKeys = DB::select($sql, $bindings);

                return ActivationKey::hydrate($activationKeys);
            }

            return null;
        } catch (\Exception $e) {
            throw new \Exception("Ошибка при выборе ключей активации: " . $e->getMessage());
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
            $keyIds  = [];
            $caseSql = 'CASE `id`';

            $bindings = [];

            foreach ($data as $group) {
                foreach ($group as $item) {
                    $keyIds[]       = $item['activation_key_id'];
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
}
