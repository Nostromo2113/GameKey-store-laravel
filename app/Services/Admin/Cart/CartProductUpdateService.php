<?php

namespace App\Services\Admin\Cart;

use App\Models\Cart;
use App\Models\Product;
use Exception;

class CartProductUpdateService
{
    /**
     * Обновляет количество продукта в корзине.
     *
     * @param array $data Входные данные, включая новое количество.
     * @param Cart $cart Корзина, которую нужно обновить.
     * @param Product $product Продукт, количество которого обновляется.
     * @return Cart Возвращает обновленную корзину.
     * @throws Exception Если произошла ошибка при обновлении.
     */
    public function update(array $data, Cart $cart, Product $product): Cart
    {
        $newQuantity = $data['quantity'];

        // Получаем количество доступных ключей активации для продукта
        $availableKeys = $product->activationKeys()->where('order_product_id', null)->count();

        // Находим продукт в корзине
        $cartProduct = $cart->products()->where('product_id', $product->id)->first();
        // Получаем кол-во продукта в корзине актуальное на данный момент
        $oldQuantity = $cartProduct->pivot->quantity;

        // Если доступных ключей меньше, чем текущее количество, обновляем до доступного количества
        if ($availableKeys < $oldQuantity) {
            // Обновляем количество в корзине до доступных ключей
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $availableKeys]);
        } elseif ($newQuantity !== $oldQuantity) {
            // Иначе обновляем до нового количества, если оно изменилось
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $newQuantity]);
        }

        $cart->load('products');

        return $cart;
    }
}
