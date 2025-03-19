<?php

namespace App\Services\Admin\User\UserCart;

use App\Models\Cart;
use App\Models\User;

class CartStoreService
{
    public function storeCart(User $user) :Cart
    {
        try {
            $userId = $user['id'];
            $cart = Cart::create([
                'user_id' => $userId,
                'total_price' => 0
            ]);
            return $cart;
        } catch (\Exception $e) {
            throw new \Exception('Ошибка при создании корзины пользователя: ' . $e->getMessage());
        }
    }
}
