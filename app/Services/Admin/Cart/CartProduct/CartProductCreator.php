<?php

namespace App\Services\Admin\Cart\CartProduct;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class CartProductCreator
{
    /**
     * Добавляет продукт в корзину.
     *
     * @param array $data Валидированные данные запроса.
     * @param Cart $cart Корзина, в которую добавляется продукт.
     * @return array Возвращает массив с результатом.
     * @throws \Exception Ошибка.
     */
    public function addProductToCart(array $data, Cart $cart): Cart
    {
        return DB::transaction(function () use ($data, $cart) {
            $productId = $data['product_id'];
            $quantity  = $data['quantity'];

            $productExist = $cart->products()->where('cart_products.product_id', $productId)->exists();

            if (!$productExist) {
                $cart->products()->attach($productId, ['quantity' => $quantity]);

                return $cart;
            }

            throw new \Exception('Продукт уже есть в корзине');
        });
    }
}
