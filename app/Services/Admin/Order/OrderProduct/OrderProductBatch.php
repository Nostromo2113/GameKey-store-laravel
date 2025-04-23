<?php

namespace App\Services\Admin\Order\OrderProduct;

use App\Models\Order;
use App\Models\Product;
use App\Services\Admin\Order\OrderActivationKey\OrderActivationKeyManager;
use Illuminate\Support\Facades\DB;

class OrderProductBatch
{
    private $keyManager;
    private $orderProductCreate;
    private $orderProductUpdate;
    private $orderProductDelete;

    public function __construct(
        OrderActivationKeyManager $orderActivationKeyManager,
        OrderProductCreator       $orderProductCreateService,
        OrderProductUpdater       $orderProductUpdateService,
        OrderProductDestroyer     $orderProductDeleteService
    ) {
        $this->keyManager         = $orderActivationKeyManager;
        $this->orderProductCreate = $orderProductCreateService;
        $this->orderProductUpdate = $orderProductUpdateService;
        $this->orderProductDelete = $orderProductDeleteService;
    }


    /**
     * Выполняет пакетное обновление заказа:
     *  1. Проверяет возможность изменения
     *  2. Определяет продукты для добавления/обновления/удаления
     *  3. Синхронизирует ключи активации
     * @param Order $order Заказ для обновления
     * @param array $data Данные для обновления
     * @param Boolean $useTransaction Из-за отстутствия отдельных контроллеров для shop, приходится использовать флаг для транзакции
     * @return Order Возвращает обновленный заказ
     * @throws \Exception
     *
     * Транзакция по требованию
     */
    public function batch(Order $order, array $data, bool $useTransaction = false): Order
    {
        if ($order->isCompleted()) {
            throw new \Exception('Заказ уже завершен. Обновление невозможно', 403);
        }

        if (!isset($data['order_products'])) {
            return $order;
        }

        $process = function () use ($order, $data) {
            $requestOrderProducts = $data['order_products'];
            $requestedProductIds  = array_column($requestOrderProducts, 'id');

            $order->load([
                'orderProducts.activationKeys',
                'orderProducts.product'
            ]);

            // Продукты присутствующие в заказе. Т.е. состояние до обновления
            $existingProducts = $order->orderProducts->whereIn('product_id', $requestedProductIds);
            // Продукты присутствующие в реквесте. Для сравнения и обновления состояния заказа
            $requestProducts = Product::whereIn('id', $requestedProductIds)
                ->with('activationKeys')
                ->get();

            $selectedActivationKeys = $this->keyManager->selectKeys($requestOrderProducts, $requestProducts, $existingProducts) ?? collect([]);

            $productsIds         = $order->orderProducts->pluck('product_id')->toArray();
            $productsToRemoveIds = array_diff($productsIds, $requestedProductIds);

            $this->orderProductCreate->addProductToOrder($requestOrderProducts, $selectedActivationKeys, $existingProducts, $order);
            $this->orderProductUpdate->updateProductQuantity($requestOrderProducts, $existingProducts, $selectedActivationKeys);
            $this->orderProductDelete->removeProductFromOrder($order, $productsToRemoveIds);

            return $order;
        };

        return $useTransaction
            ? DB::transaction($process)
            : $process();
    }
}
