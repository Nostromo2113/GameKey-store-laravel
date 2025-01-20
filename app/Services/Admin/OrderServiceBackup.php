<?php

namespace App\Services\Admin;

use App\Models\ActivationKey;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderServiceBackup
{
    public function update($order, $data)
    {
        $requestOrderProducts = $data['order_products'];

        DB::beginTransaction();
        $orderProducts = $order->orderProducts;

        $orderProductIds = $orderProducts->pluck('product_id')->toArray(); // Список id из заказа

        // Id выбранных для удаления продуктов
        $requestedIds = collect($requestOrderProducts)->pluck('id')->toArray();
        $orderProductIdsToRemove = collect($orderProductIds)->diff($requestedIds)->toArray();
        ///
        /**
         * Удаление продукта из заказа по id.
         * Если id product в json отсутствует,
         * продукт следует удалить из заказа
         **/
        // В начале особождаем ключи удаляемого продукта
        $this->releaseActivationKeys($order, $orderProductIdsToRemove);
        // Удаляем сам продукт
        $this->removeProductFromOrder($order, $orderProductIdsToRemove);

        /**
         * Проходимся по продуктам в реквесте
         **/

        $requestOrderProductsIds = collect($requestOrderProducts)->pluck('id')->toArray();
        $filteredOrderProducts = $orderProducts->whereIn('product_id', $requestOrderProductsIds);
        $products = Product::whereIn('id', $requestOrderProductsIds)->get()->keyBy('id');
        $selectedActivationKeys = $this->selectActivationKeys($requestOrderProducts, $filteredOrderProducts);

        foreach ($requestOrderProducts as $requestItem) {
            $orderProduct = $filteredOrderProducts->firstWhere('product_id', $requestItem['id']);

            //Если продукт есть в заказе, то регулируем количество ключей к продукту(т.е. определяем кол-во экземпляра продукта в заказе)
            if ($orderProduct) {
                $this->updateOrderProductQuantity($orderProduct, $requestItem);

                //Если продукта в заказе нет, но он есть в реквесте, добавляем его в заказ
            } else {
                $product = $products[$requestItem['id']] ?? null;
                if ($product) {
                    $this->addProductToOrder($order, $product, $requestItem);
                }
            }
            if($orderProduct){
                $this->recordProductQuantity($orderProduct);
            }

        }
        $order->load('orderProducts');
        $this->calcTotalPrice($order);
        DB::commit();
    }

    //Вызывается при удалении продукта из заказа
    private function releaseActivationKeys($order, $orderProductIdsToRemove)
    {
        // Получаем все ключи активации, связанные с продуктами, которые нужно удалить
        $keysToRelease = $order->orderProducts()
            ->whereIn('product_id', $orderProductIdsToRemove)
            ->with('activationKeys') // Загружаем связанные ключи активации
            ->get()
            ->flatMap(function ($orderProduct) {
                // Возвращаем все ключи активации для этого продукта
                return $orderProduct->activationKeys;
            });
        $this->assignActivationKeys($keysToRelease);
    }

    private function removeProductFromOrder($order, $orderProductIdsToRemove)
    {
        $order->orderProducts()
            ->whereIn('product_id', $orderProductIdsToRemove)
            ->delete(); // Удаляем записи из таблицы order_products, остальные удалятся через onDelete
    }

    private function updateOrderProductQuantity($orderProduct, $requestItem)
    {
        $activationKeys = $orderProduct->activationKeys;
        $product = $orderProduct->product;
        $currentQuantity = $activationKeys->count(); // Количество ключей, уже привязанных к заказу
        $requestedQuantity = $requestItem['quantity']; // Количество, которое пришло с фронта

        if ($requestedQuantity > $currentQuantity) {
            // Если нужно добавить ключи
            $calcQuantity = $requestedQuantity - $currentQuantity;
            $activationKeysStore = ActivationKey::where('product_id', $product->id)
                ->whereNull('order_product_id')
                ->limit($calcQuantity)
                ->get();

            if ($activationKeysStore->count() < $calcQuantity) {
                dd("Нужно {$calcQuantity} ключей, а в наличии {$activationKeysStore->count()} свободных ключей");
            } else {
                // Привязываем ключи к текущему продукту заказа
                $this->assignActivationKeys($activationKeysStore, $orderProduct);
            }
            //Записываем количество привязанных ключей в order_products
            $orderProduct->load('activationKeys');
        } elseif ($requestedQuantity < $currentQuantity) {
            // Если количество ключей нужно уменьшить
            $calcQuantityToRemove = $currentQuantity - $requestedQuantity;

            // Извлекаем ключи, которые нужно освободить
            $keysToDetach = $activationKeys->take($calcQuantityToRemove);

            // Освобождаем ключи
            $this->assignActivationKeys($keysToDetach);

            //Записываем количество привязанных ключей в order_products
            $orderProduct->load('activationKeys');
        }
    }


    private function addProductToOrder($order, $product, $requestItem)
    {
        $orderProduct = $order->orderProducts()->create([
            'product_id' => $product->id,
            'order_id' => $order->id,       // Передаем order_id, чтобы связать продукт с заказом
        ]);

        // Теперь добавляем ключи для нового продукта
        $calcQuantity = $requestItem['quantity'];
        $activationKeysStore = ActivationKey::where('product_id', $product->id)
            ->whereNull('order_product_id')
            ->limit($calcQuantity)
            ->get();

        if ($activationKeysStore->count() < $calcQuantity) {
            dd("Нужно {$calcQuantity} ключей, а в наличии {$activationKeysStore->count()} свободных ключей");
        } else {
            $this->assignActivationKeys($activationKeysStore, $orderProduct);
            $this->recordProductQuantity($orderProduct);
        }
    }





    //Менеджер ключей. Добавление в заказ, освобождение от заказа
    private function assignActivationKeys($keys, $orderProduct = null)
    {
        $orderProductId = $orderProduct ? $orderProduct->id : null;

        if ($keys->isEmpty()) {
            return;
        }

        $keyIds = $keys->pluck('id');
        DB::table('activation_keys')->whereIn('id', $keyIds)->update(['order_product_id' => $orderProductId]);
    }

    private function recordProductQuantity($orderProduct)
    {
        $orderProduct->update(['quantity' => $orderProduct->activationKeys->count()]);
    }

    private function calcTotalPrice($order)
    {
        $orderProducts = $order->orderProducts;
        $totalPrice = 0;
        foreach($orderProducts as $orderProduct) {
            $quantityPrice = $orderProduct->product->price * $orderProduct->quantity;
            $totalPrice += $quantityPrice;
        }
        $order->update(['total_price' => $totalPrice]);
    }


    private function selectActivationKeys($requestOrderProducts, $filteredOrderProducts)
    {
        $sql = '';
        foreach($requestOrderProducts as $index => $requestOrderProduct){
            $orderProduct = $filteredOrderProducts->firstWhere('product_id', $requestOrderProduct['id']);
            $currentQuantity = $orderProduct->activationKeys->count();
            $calcQuantity = (int)$requestOrderProduct['quantity'] - (int)$currentQuantity;
            dump($calcQuantity);
            if($calcQuantity > 0) {
                $sql .= "
                (
                SELECT * FROM activation_keys
                WHERE product_id = {$requestOrderProduct['id']} AND order_product_id IS NULL
                limit {$requestOrderProduct['quantity']}
                )
                ";
                if ($index < count($requestOrderProduct) - 1) {
                    $sql .= ' UNION ALL ';
                }
                dump($sql);
            }
        }
        if(!empty($sql)) {
            $activationKeys = DB::select($sql);
            $activationKeys = ActivationKey::hydrate($activationKeys);
            dd('finish');
            return $activationKeys;
        } else {
            return;
        }
    }
}
