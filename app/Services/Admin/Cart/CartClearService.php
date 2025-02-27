<?php

namespace App\Services\Admin\Cart;

use App\Models\Cart;

class CartClearService
{
    public function clearCart(int $userId): void
    {
        try {
            $cart = Cart::where('user_id', $userId)->firstOrFail();
            $cart->products()->detach();
        } catch(\Exception $e) {
            throw new \Exception("Ошибка при очистке корзины: " . $e->getMessage());
        }
    }
}
