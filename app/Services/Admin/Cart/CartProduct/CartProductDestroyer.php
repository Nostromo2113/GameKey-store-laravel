<?php

namespace App\Services\Admin\Cart\CartProduct;

use App\Models\Cart;

class CartProductDestroyer
{
    public function deleteProductInCart(Cart $cart, int $productId): Cart
    {
        $cart->products()->detach($productId);

        return $cart;
    }

    public function deleteAllProductsInCart(int $userId): void
    {
        $cart = Cart::where('user_id', $userId)->firstOrFail();
        $cart->products()->detach();
    }
}
