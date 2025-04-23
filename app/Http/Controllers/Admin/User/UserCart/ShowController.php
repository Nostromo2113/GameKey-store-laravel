<?php

namespace App\Http\Controllers\Admin\User\UserCart;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\UserCart\UserCartResource;
use App\Models\User;

class ShowController extends Controller
{
    public function __invoke(User $user)
    {
        $cart = $user->cart;

        $cart->load('products.activationKeys');

        return response()->json(new UserCartResource($cart), 200);
    }
}
