<?php
namespace App\Http\Controllers\Admin\Cart;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CartResource;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ShowController extends Controller
{
    public function __invoke(Cart $cart)
    {
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        $cart->load('products.activationKeys');
        return response()->json(new CartResource($cart), 200);
    }
}
