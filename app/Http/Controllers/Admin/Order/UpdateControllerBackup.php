<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UpdateRequest;
use App\Models\ActivationKey;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateControllerBackup extends Controller
{
    public function __invoke(Order $order, UpdateRequest $request)
    {
        $data = $request->validated();
        $orderProductsData = $data['order_products'];
        $productsIds = array_column($orderProductsData, 'id');
        $productsInDb = Product::whereIn('id', $productsIds)->get()->keyBy('id');
        $productsOldQuantity = $this->getOldProductsQuantity($order);

        //////////////////////////////////
        DB::beginTransaction();
        try {
            // Процесс сбора продуктов для заказа
            $orderProducts = $this->getOrderProducts($orderProductsData, $productsInDb);
        } catch (\Exception $e) {
            // Возвращаем ошибку в случае отсутствующего продукта
            return response()->json([
                'message' => $e->getMessage(),
                'error_code' => $e->getCode()
            ], $e->getCode());
        }

        // Синхронизация продуктов в заказе
        $order->products()->sync($orderProducts);
        //Обновляем данные
        $order->load('products');
        $totalPrice = $this->updateOrderPrices($order);
        $this->updateProductStock($productsOldQuantity, $orderProductsData, $productsIds);

        $order->update(['total_price' => round($totalPrice, 2)]);
        DB::commit();
        return response()->json([
            'message' => 'Order updated successfully',
            'data' => $order
        ], 200);
    }

    /**
     * Метод для приведения продуктов заказа
     */
    private function getOrderProducts(array $orderProductsData, $productsInDb)
    {
        if (!$productsInDb instanceof Collection) {
            throw new \InvalidArgumentException('Expected $productsInDb to be an array or Collection.');
        }
        return array_reduce($orderProductsData, function ($carry, $product) use ($productsInDb) {
            $productInDb = $productsInDb->get($product['id']); // Используем get() вместо прямого доступа
            if (!$productInDb) {
                // Если продукт не найден, выбрасываем исключение
                throw new \Exception("Product with ID {$product['id']} not found.", 404);
            }
            $carry[$product['id']] = [
                'quantity' => $product['quantity'],
                'price' => $productInDb->price,  // Берем цену из базы данных
            ];
            return $carry;
        }, []);
    }


    /**
     * Основной метод для обновления данных на складе
     */
    private function updateProductStock(array $productsOldQuantity, array $productsOrderData, array $productsIds): void
    {
        //набор id продуктов в заказе до обновления данных
        $removableProductIds = array_column($productsOldQuantity, 'id');
        // diff вернет коллекцию с новыми элементами, values обнулит индексы.
        $newProductsIds = collect($productsIds)
            ->diff(collect($removableProductIds))
            ->values();
        $removableProductIds = collect($removableProductIds)
            ->diff(collect($productsIds))
            ->values();
        // Обновляем кол-во продуктов на складе только для новых продуктов в заказе.
        if ($newProductsIds->count() > 0) {
            $this->adjustStockForAddedOrRemovedItems($newProductsIds, $productsOrderData, '-');
        }
        // Для удаленных из заказа продуктов
        if ($removableProductIds->count() > 0) {
            $this->adjustStockForAddedOrRemovedItems($removableProductIds, $productsOrderData, '+', $productsOldQuantity);
            //Перезаписали переменную для дальнейшей проверки
            $newProductsIds = $newProductsIds->merge($removableProductIds);
        }

        // меняем кол-во продуктов на складе при изменении кол-ва продуктов уже имеющихся в заказе
        $this->adjustStockForQuantityChange($productsOrderData, $productsOldQuantity, $newProductsIds);

    }

    /**
     * Вспомогательный метод обновления склада при добавлении/удалении экземпляров
     */
    private function adjustStockForAddedOrRemovedItems(Collection $productsIds, array $productsOrderData, string $operator, array $productsOldQuantity = []): void
    {
        $selectedProducts = Product::whereIn('id', $productsIds)->get();
        $updates = [];
        //dd($selectedProducts);
        $productsIds->each(function ($productId) use ($productsOrderData, $operator, $productsOldQuantity, $selectedProducts, &$updates) {
            $selectedProduct = $selectedProducts->firstWhere('id', $productId);
            if ($selectedProduct) {
                // Находим индекс в обновленных продуктах, для получения правильного количества
                $updatedProductAmount = count($productsOldQuantity) < 1 ?
                    collect($productsOrderData)->firstWhere('id', $productId)['quantity'] :
                    collect($productsOldQuantity)->firstWhere('id', $productId)['quantity'];
                if ($updatedProductAmount) {
                    $newAmount = match ($operator) {
                        '+' => $selectedProduct->amount + abs($updatedProductAmount),
                        '-' => $selectedProduct->amount - abs($updatedProductAmount),
                        default => throw new \InvalidArgumentException("Invalid operator '{$operator}'"),
                    };
                    if ($newAmount < 0) {
                        throw new \Exception('Insufficient stock for product ID ' . $selectedProduct->id);
                    } else {
                        $updates[] = [
                            'id' => $productId,
                            'new_amount' => $newAmount
                        ];
                    }
                }
            }
        });//finish each

        // SQL запрос с использованием CASE
        // Начинаем формировать запрос
        $updateQuery = "UPDATE products SET amount = CASE id ";

        // Добавляем условия для каждого продукта
        foreach ($updates as $update) {
            $updateQuery .= "WHEN {$update['id']} THEN {$update['new_amount']} ";
        }

        // Закрываем конструкцию CASE
        $updateQuery .= "END WHERE id IN (" . implode(',', $productsIds->toArray()) . ")";

        // Печатаем запрос для проверки
        //        dd($updateQuery);

        // Выполняем необработанный SQL-запрос
        DB::statement($updateQuery);
    }

    /**
     * Вспомогательный метод обновления склада при изменении кол-во экземпляров на складе
     */
    private function adjustStockForQuantityChange($productsOrderData, $productsOldQuantity, $newProductsIds): void
    {
        foreach ($productsOrderData as $updatedProduct) {
            // Извлекаем старое количество продукта из заказа, если оно было, иначе 0.
            $oldQuantity = collect($productsOldQuantity)
                ->firstWhere('id', $updatedProduct['id'])['quantity'] ?? 0;

            // Преобразуем новое количество из обновленного заказа в целое число.
            $newQuantity = (int)$updatedProduct['quantity'];

            // Вычисляем разницу между старым и новым количеством
            $quantityDifference = $oldQuantity - $newQuantity;

            // Загружаем модель продукта по его ID
            $product = Product::find($updatedProduct['id']);

            if (!$product) {
                throw new \Exception('Product not found: ' . $updatedProduct['id']);
            }

            // При увеличении кол-ва продукта
            if ($quantityDifference < 0 && !$newProductsIds->contains($product->id)) {
                // Если разница отрицательная и продукт не в списке новых товаров, обновляем его количество
                $newAmount = $product->amount - abs($quantityDifference);

                // Проверка на достаточность количества
                $newAmount < 0 ?
                    throw new \Exception('Insufficient stock for product ID ' . $product->id) :
                    $product->update(['amount' => $newAmount]);
                // При уменьшении кол-ва продукта
            } elseif ($quantityDifference > 0 && !$newProductsIds->contains($product->id)) {
                // Если разница положительная и продукт не в списке новых товаров, возвращаем товар на склад
                $newAmount = $product->amount + abs($quantityDifference);
                $product->update(['amount' => $newAmount]);
            }

            // Логирование изменения
            Log::debug("Stock updated for Product ID {$product->id}: Difference: {$quantityDifference}, New Stock: {$product->amount}");
        }
    }


    /**
     * Обновление в таблице order_products цены за кол-во экземпляров.
     */
    private function updateOrderPrices(Order $order): float
    {
        $totalPrice = 0;

        foreach ($order->products as $product) {
            $price = (float)$product['price'];
            $quantity = (int)$product->pivot->quantity;

            $quantityPrice = round($price * $quantity, 2);
            $totalPrice += $quantityPrice;

            $order->products()->updateExistingPivot($product->id, ['price' => $quantityPrice]);
        }

        return $totalPrice;
    }


    /**
     * Получение данных о кол-ве экземпляров до изменения таблицы. Нужно для корректного обновления склада
     */
    private function getOldProductsQuantity($order): array
    {
        $productsOldQuantity = $order->products->map(function ($product) {
            return [
                'id' => $product->id,
                'quantity' => $product->pivot->quantity,
            ];
        })->toArray();
        return $productsOldQuantity;
    }

}
