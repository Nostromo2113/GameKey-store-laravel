<?php

namespace App\Http\Controllers\Admin\Cart\Products;

use App\Http\Controllers\Controller;
use App\Models\Cart;

class DestroyController extends Controller
{
    public function __invoke(Cart $cart, $productId)
    {
        try {
            $cart->products()->detach($productId);

            return response()->json(['message' => 'Product removed from cart']);
        } catch (\Exception $e) {
            return response()->json(['error when deleting a product from the cart' => $e->getMessage()], 500);
        }
    }
}
