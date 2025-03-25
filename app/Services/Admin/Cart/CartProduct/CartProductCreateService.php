<?php

namespace App\Services\Admin\Cart\CartProduct;

use App\Models\Cart;

class CartProductCreateService
{
    /**
     * Добавляет продукт в корзину.
     *
     * @param array $data Валидированные данные запроса.
     * @param Cart $cart Корзина, в которую добавляется продукт.
     * @return array Возвращает массив с результатом.
     * @throws \Exception Ошибка.
     */
    public function storeProductInCart(array $data, Cart $cart): array
    {
        try {
            $productId = $data['product_id'];
            $quantity = $data['quantity'];

            $productExist = $cart->products()->where('cart_products.product_id', $productId)->exists();

            if (!$productExist) {
                $cart->products()->attach($productId, ['quantity' => $quantity]);

                return [
                    'success' => true,
                    'message' => 'Product added to cart',
                    'cart' => $cart->load('products'),
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Product already exists in cart',
                ];
            }
        } catch (\Exception $e) {
            throw new \Exception('Ошибка при добавлении продукта в заказ: ' . $e->getMessage());
        }
    }
}
