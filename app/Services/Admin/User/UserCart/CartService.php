<?php

namespace App\Services\Admin\User\UserCart;

use App\Models\Cart;
use App\Models\User;

class CartService
{
    private $cartStoreService;

    public function __construct(CartStoreService $cartStoreService)
    {
        $this->cartStoreService = $cartStoreService;
    }

    public function store (User $user): Cart
    {
       return $this->cartStoreService->storeCart($user);
    }

}
