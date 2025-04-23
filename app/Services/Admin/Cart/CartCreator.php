<?php

namespace App\Services\Admin\Cart;

use App\Models\Cart;
use App\Models\User;

class CartCreator
{
    public function store(User $user): Cart
    {
        $userId = $user['id'];
        $cart   = Cart::create([
            'user_id' => $userId,
        ]);

        return $cart;
    }
}
