<?php

namespace App\Services\Admin;

use App\Models\ActivationKey;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    //Работает обновление кол-во ключей, добавление продуктов. Нужно реализовать привязку ключей к добавленным продуктам
    /**
     * Обновление заказа.
     *
     * @param Order $order - объект заказа
     * @param array $data - данные из запроса
     */
    public function update(Order $order, array $data): void
    {
        $requestOrderProducts = $data['order_products'];

        DB::beginTransaction();
        // ID присутствующих продуктов в заказе до применения изменений.
        $orderProducts = $order->orderProducts;
        $orderProductIds = $orderProducts->pluck('product_id')->toArray(); // Список id из заказа

        // ID выбранных для удаления продуктов. т.е. id продуктов, которые присутствуют в order, но отсутствуют в request.
        $requestOrderProductsIds = collect($requestOrderProducts)->pluck('id')->toArray();
        $orderProductIdsToRemove = collect($orderProductIds)->diff($requestOrderProductsIds)->toArray();

        // Удаление продукта из заказа.
        $this->performRemoveProduct($order, $orderProductIdsToRemove);

        // Получаем модели пивот таблицы orderProducts по id присутствующих продуктов.
        $filteredOrderProducts = $orderProducts->whereIn('product_id', $requestOrderProductsIds);
        // Достаем из базы нужные для работы с заказом ключи. Удаляемые из заказа и добавляемые в заказ.
        $selectedActivationKeys = $this->selectActivationKeys($requestOrderProducts, $filteredOrderProducts);

        // Регулирование количества экземпляров и добавление новых продуктов.
        $this->performUpdateProduct($requestOrderProducts, $filteredOrderProducts, $selectedActivationKeys);
        $this->performAddProduct(
            $requestOrderProducts,
            $selectedActivationKeys,
            $filteredOrderProducts,
            $order
        );
        $order->load('orderProducts');
        $this->calcTotalPrice($order);
        DB::commit();
    }

    /**
     * Удаляет продукты из заказа.
     *
     * @param Order $order - текущий заказ
     * @param array $orderProductIdsToRemove - ID продуктов для удаления
     */
    private function performRemoveProduct(Order $order, array $orderProductIdsToRemove): void
    {
        // Освобождаем ключи удаляемых из заказа продуктов.
        $this->releaseActivationKeys($orderProductIdsToRemove);
        // Удаляем сам продукт из заказа.
        $this->removeProductFromOrder($order, $orderProductIdsToRemove);
    }

    /**
     * Обновляет существующие продукты в заказе или добавляет новые.
     *
     * @param array $requestOrderProducts - данные из запроса
     * @param Collection $filteredOrderProducts - продукты, уже присутствующие в заказе
     * @param Collection|null $selectedActivationKeys - ключи, выбранные для работы с заказом
     */
    private function performUpdateProduct(
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
                $activationKeysToUpdate = array_merge($activationKeysToUpdate, $this->prepareKeysForBinding($orderProduct, $requestItem, $selectedActivationKeys));
            }
            //Это пересчитаем в конце основного метода. Изменим порядок
            if ($orderProduct) {
                // Фиксируем количество привязанных ключей.
                //   $this->recordProductQuantity($orderProduct);
            }
        }
        if (count($activationKeysToUpdate) > 0) {
            $this->bindActivationKeys($activationKeysToUpdate);
        }


    }

    private function performAddProduct(
        array       $requestOrderProducts,
        ?Collection $selectedActivationKeys,
                    $filteredOrderProducts,
        Order       $order
    )
    {
        $filteredOrderProductsById = $filteredOrderProducts->keyBy('product_id');

        //Сюда добавляем id продуктов которые присутствуют в реквесте но отсутствуют в заказе
        $productsToAdd = array_column(array_filter($requestOrderProducts, function ($requestOrderProduct) use ($filteredOrderProductsById) {
            return !$filteredOrderProductsById->has($requestOrderProduct['id']);
        }), 'id');
        //Преобразуем массив для массовой записи
        $productsToAdd = array_map(function ($productId) {
            return [
                'product_id' => $productId
            ];
        }, $productsToAdd);
        $addedOrderProducts = $order->orderProducts()->createMany($productsToAdd);
        $activationKeysToAdd = [];
        foreach ($requestOrderProducts as $requestOrderProduct) {
            $orderProduct = $addedOrderProducts->where('product_id', $requestOrderProduct['id'])->first();
            if ($orderProduct) {
                $activationKeysToAdd = array_merge($activationKeysToAdd, $this->prepareKeysForBinding($orderProduct, $requestOrderProduct, $selectedActivationKeys));
            }
        }
        if (count($activationKeysToAdd) > 0) {
            $this->bindActivationKeys($activationKeysToAdd);
        }

    }

    /**
     * Освобождает ключи продуктов удаляемых из заказа.
     *
     * @param array $orderProductIdsToRemove - ID продуктов для удаления
     */
    private function releaseActivationKeys(array $orderProductIdsToRemove): void
    {
        DB::table('activation_keys')
            ->whereIn('order_product_id', $orderProductIdsToRemove)
            ->update(['order_product_id' => null]);
    }

    /**
     * Удаляет записи продуктов из заказа.
     *
     * @param Order $order - текущий заказ
     * @param array $orderProductIdsToRemove - ID продуктов для удаления
     */
    private function removeProductFromOrder(Order $order, array $orderProductIdsToRemove): void
    {
        $order->orderProducts()
            ->whereIn('product_id', $orderProductIdsToRemove)
            ->delete();
    }

    /**
     * Обновляет количество ключей продукта в заказе.
     *
     * @param mixed $orderProduct - продукт заказа
     * @param array $requestItem - данные из запроса
     * @param Collection|null $selectedActivationKeys - ключи для работы
     */
    private function prepareKeysForBinding($orderProduct, array $requestItem, ?Collection $selectedActivationKeys)
    {
        $currentActivationKeys = $orderProduct->activationKeys;
        $product = $orderProduct->product;
        $currentQuantity = $currentActivationKeys->count(); // Количество ключей в текущем заказе.
        $requestedQuantity = $requestItem['quantity']; // Требуемое количество.
        $activationKeysToUpdate = [];
        if ($requestedQuantity > $currentQuantity) {
            // Добавляем ключи.
            $calcQuantity = $requestedQuantity - $currentQuantity;
            $selectedProductActivationKeys = $selectedActivationKeys->where('product_id', $product->id);
            if ($selectedProductActivationKeys->count() < $calcQuantity) {
                dd("Нужно {$calcQuantity} ключей, а в наличии {$selectedProductActivationKeys->count()} свободных ключей");
            } else {
                $activationKeysToUpdate[] = $this->assignActivationKeys($selectedProductActivationKeys, $orderProduct);
                //   dd('увеличиваем', $activationKeysToUpdate);
            }
        } elseif ($requestedQuantity < $currentQuantity) {
            // Уменьшаем количество ключей.
            $keysToDetach = $selectedActivationKeys->whereNotNull('order_product_id');
            $activationKeysToUpdate[] = $this->assignActivationKeys($keysToDetach);
        }
        return $activationKeysToUpdate;
    }

    /**
     * Добавляет новый продукт в заказ.
     *
     * @param Order $order - текущий заказ
     * @param Product $product - продукт для добавления
     * @param array $requestItem - данные из запроса
     * @param Collection|null $selectedActivationKeys - ключи для добавления
     */
    private function addProductToOrder(
        Order       $order,
        Product     $product,
        array       $requestItem,
        ?Collection $selectedActivationKeys
    ): void
    {
        $orderProduct = $order->orderProducts()->create([
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]);

        $calcQuantity = $requestItem['quantity'];
        $selectedActivationKey = $selectedActivationKeys->where('product_id', $product->id)->whereNull('order_product_id');
        if ($selectedActivationKey->count() < $calcQuantity) {
            dd("Нужно {$calcQuantity} ключей, а в наличии {$selectedActivationKey->count()} свободных ключей");
        } else {
            $this->assignActivationKeys($selectedActivationKey, $orderProduct);
            $this->recordProductQuantity($orderProduct);
        }
    }

    /**
     * Готовим массив продуктов к добавлению в заказ
     **/
    private function prepareProductsForOrder(Order $order, Product $product)
    {
        $orderProduct = [
            'product_id' => $product->id,
            'order_id' => $order->id,
        ];
        return $orderProduct;
    }


    /**
     * Привязывает и отвязывает ключи
     *
     * @param Collection $keys - отобранные ключи активации
     * @mixded $orderProduct - модель пивот таблицы
     */
    private function assignActivationKeys(Collection $keys, $orderProduct = null)
    {
        $orderProductId = $orderProduct ? $orderProduct->id : null;
        if ($keys->isEmpty()) {
            return;
        }
        $keyIds = $keys->pluck('id');
        $keysToUpdate = [];
        foreach ($keyIds as $keyId) {
            $keysToUpdate[] = [
                'activation_key_id' => $keyId,
                'order_product_id' => $orderProductId
            ];
        }
        return $keysToUpdate;
    }

    private function bindActivationKeys($data)
    {
        if (empty($data)) {
            return;
        }

        $keyIds = [];
        $caseSql = 'CASE `id`';

        foreach ($data as $group) {
            foreach ($group as $item) {
                $keyIds[] = $item['activation_key_id'];
                $orderProductId = $item['order_product_id'] ?? 'NULL'; // Используем 'NULL' для пустых значений
                $caseSql .= " WHEN {$item['activation_key_id']} THEN {$orderProductId}";
            }
        }

        $caseSql .= ' END';

        $keyIdsList = implode(',', $keyIds);

        $sql = "
        UPDATE `activation_keys`
        SET `order_product_id` = {$caseSql}
        WHERE `id` IN ({$keyIdsList});
    ";

        DB::statement($sql);
    }


    /**
     * Устанавливет quantity продукту в заказе по кол-ву привязанных к продукту ключей
     *
     * @param Collection $keys - отобранные ключи активации
     * @mixded $orderProduct - модель пивот таблицы
     */
    private function recordProductQuantity($orderProduct): void
    {
        $orderProduct->update(['quantity' => $orderProduct->activationKeys->count()]);
    }


    /**
     * Устанавливает total price для таблицы orders. Получаем сумму всех продуктов, учитывая кол-во экземпляров каждого продукта в заказе
     *
     * @param Order $order - текущий заказ
     */
    private function calcTotalPrice(Order $order): void
    {
        $orderProducts = $order->orderProducts;
        $totalPrice = $orderProducts->reduce(function ($carry, $orderProduct) {
            return $carry + $orderProduct->product->price * $orderProduct->activationKeys->count();
        }, 0);
        $order->update(['total_price' => $totalPrice]);
    }

    /**
     * Отбирает необходимые для работы с заказом ключи. ключи свободные подележат привязки к заказу, ключи "занятые", т.е. с записанным id пивот таблицы подлежат удалению из заказа
     *
     * @param array $requestOrderProducts - данные из запроса
     * @param Collection $filteredOrderProducts - продукты, уже присутствующие в заказе
     */
    private function selectActivationKeys(array $requestOrderProducts, Collection $filteredOrderProducts): ?Collection
    {
        $bindings = [];
        $sqlParts = [];

        foreach ($requestOrderProducts as $requestOrderProduct) {
            $orderProduct = $filteredOrderProducts->firstWhere('product_id', $requestOrderProduct['id']);
            $currentQuantity = $orderProduct ? (int)$orderProduct->activationKeys->count() : 0;
            $requestQuantity = (int)$requestOrderProduct['quantity'];
            $calcQuantity = $requestQuantity - $currentQuantity;

            if ($calcQuantity > 0) {
                $sqlParts[] = "
                (SELECT * FROM activation_keys
                WHERE product_id = ? AND order_product_id IS NULL
                LIMIT ?)
            ";
                $bindings[] = $requestOrderProduct['id'];
                $bindings[] = $calcQuantity;
            } elseif ($calcQuantity < 0) {
                $calcQuantity = abs($calcQuantity);
                $sqlParts[] = "
                (SELECT * FROM activation_keys
                WHERE product_id = ? AND order_product_id = ? AND order_product_id IS NOT NULL
                LIMIT ?)
            ";
                $bindings[] = $requestOrderProduct['id'];
                $bindings[] = $orderProduct->id;
                $bindings[] = $calcQuantity;
            }
        }

        if (!empty($sqlParts)) {
            $sql = implode(' UNION ALL ', $sqlParts);
            // Выполняем запрос с привязками
            $activationKeys = DB::select($sql, $bindings);
            return ActivationKey::hydrate($activationKeys);
        }

        return null;
    }
}

