<?php
namespace App\Http\Controllers\Admin\User\UserCart;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\UserCart\UserCartResource;
use App\Models\User;

class ShowController extends Controller
{
    public function __invoke(User $user)
    {
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $cart = $user->cart;

        $cart->load('products.activationKeys');
        return response()->json(new UserCartResource($cart), 200);
    }
}
