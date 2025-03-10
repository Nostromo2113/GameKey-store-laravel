<?php

namespace App\Services\Admin\Cart\CartProduct;

use App\Models\Cart;

class CartProductDestroyService
{
    public function deleteProductInCart(Cart $cart, int $productId) :void
    {
        try {
            $cart->products()->detach($productId);
        } catch (\Exception $e) {
            throw new \Exception("Ошибка при удалении продукта из корзины: " . $e->getMessage());
        }
    }

    public function deleteAllProductsInCart(int $userId): void
    {
        try {
            $cart = Cart::where('user_id', $userId)->firstOrFail();
            $cart->products()->detach();
        } catch(\Exception $e) {
            throw new \Exception("Ошибка при очистке корзины: " . $e->getMessage());
        }
    }
}
