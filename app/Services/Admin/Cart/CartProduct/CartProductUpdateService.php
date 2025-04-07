<?php

namespace App\Services\Admin\Cart\CartProduct;

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
    public function updateProductQuantityInCart(array $data, Cart $cart, Product $product): Cart
    {
        try {
            $newQuantity = $data['quantity'];
            // Получаем количество доступных ключей активации для продукта
            $availableKeys = $product->activationKeys->where('order_product_id', null)->count();

            // Находим продукт в корзине
            $cartProduct = $cart->products->firstWhere('id', $product->id);

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
            return $cart;
        } catch(\Exception $e){
            throw new \Exception('Ошибка про обновлении продукта в корзине: ' . $e);
        }
    }
}
