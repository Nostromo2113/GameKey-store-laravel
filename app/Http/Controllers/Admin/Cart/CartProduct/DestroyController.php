<?php

namespace App\Http\Controllers\Admin\Cart\CartProduct;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\UserCart\UserCartResource;
use App\Models\Cart;
use App\Services\Admin\Cart\CartProduct\CartProductService;

class DestroyController extends Controller
{
    private $cartProductService;

    public function __construct(CartProductService $cartProductService)
    {
        $this->cartProductService = $cartProductService;
    }
    public function __invoke(Cart $cart, $productId)
    {
        $updatedCart = $this->cartProductService->destroy($cart, $productId);

        return response()->json([
            'message' => 'Продукт удален из корзины',
            'data'    => new UserCartResource($updatedCart)
        ], 200);
    }
}
