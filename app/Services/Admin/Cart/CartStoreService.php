<?php

namespace App\Services\Admin\Cart;

use App\Models\Cart;
use App\Models\User;

class CartStoreService
{
    public function store(User $user) :Cart
    {
        try {
            $userId = $user['id'];
            $cart = Cart::create([
                'user_id' => $userId,
                'total_price' => 0
            ]);
            return $cart;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
