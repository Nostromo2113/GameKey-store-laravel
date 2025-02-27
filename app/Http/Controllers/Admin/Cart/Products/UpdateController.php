<?php

namespace App\Http\Controllers\Admin\Cart\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\UpdateRequest;
use App\Http\Resources\Admin\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Services\Admin\Cart\CartProductUpdateService;

class UpdateController extends Controller
{
    public function __construct(CartProductUpdateService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function __invoke(UpdateRequest $request, Cart $cart, Product $product)
    {
        $data = $request->validated();
        try {
            $cart = $this->cartService->update($data, $cart, $product);
            return response()->json([
                'data' => new CartResource($cart),
                'message' => 'The product in the cart has been updated'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['Error when updating a product in the cart' => $e->getMessage()], 500);
        }
    }
}
