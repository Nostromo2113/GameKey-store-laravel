<?php

namespace App\Http\Controllers\Admin\Cart\CartProduct;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\UserCart\UserCartResource;
use App\Models\Cart;
use App\Services\Admin\Cart\CartProduct\CartProductDestroyer;
use App\Services\Admin\Cart\CartProduct\CartProductService;

class DestroyController extends Controller
{
    private $cartProductDestroyer;

    public function __construct(CartProductDestroyer $cartProductDestroyer)
    {
        $this->cartProductDestroyer = $cartProductDestroyer;
    }
    public function __invoke(Cart $cart, $productId)
    {
        $updatedCart = $this->cartProductDestroyer->deleteProductInCart($cart, $productId);

        return response()->json([
            'message' => 'Продукт удален из корзины',
            'data'    => new UserCartResource($updatedCart)
        ], 200);
    }
}
