<?php
namespace App\Http\Controllers\Admin\Cart;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CartResource;
use App\Models\User;

class ShowController extends Controller
{
    public function __invoke(User $user)
    {
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $cart = $user->cart;
        return response()->json(new CartResource($cart), 200);
    }
}
