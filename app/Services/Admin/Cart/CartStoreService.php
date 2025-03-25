<?php

namespace App\Services\Admin\Cart;

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
            ]);
            return $cart;
        } catch (\Exception $e) {
            throw new \Exception('Ошибка при создании корзины пользователя: ' . $e->getMessage());
        }
    }
}
