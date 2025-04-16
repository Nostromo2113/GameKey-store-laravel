<?php

namespace App\Services\Admin\Cart;

use App\Models\Cart;
use App\Models\User;

class CartStoreService
{
    public function storeCart(User $user): Cart
    {
        $userId = $user['id'];
        $cart   = Cart::create([
            'user_id' => $userId,
        ]);

        return $cart;
    }
}
