<?php

namespace App\Http\Controllers\Admin\Cart\CartProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\CartProduct\StoreRequest;
use App\Http\Resources\Admin\User\UserCart\UserCartResource;
use App\Models\Cart;
use App\Services\Admin\Cart\CartProduct\CartProductService;

class StoreController extends Controller
{
    private $cartProductService;

    public function __construct(CartProductService $cartProductService)
    {
        $this->cartProductService = $cartProductService;
    }


    public function __invoke(Cart $cart, StoreRequest $request)
    {
        $data = $request->validated();
        $cart = $this->cartProductService->store($data['product'], $cart);

        $cart->load('products.activationKeys');

        return response()->json([
            'message' => 'Продукт добавлен в корзину',
            'data'    => new UserCartResource($cart),
        ], 200);
    }
}
