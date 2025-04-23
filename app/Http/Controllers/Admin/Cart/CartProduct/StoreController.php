<?php

namespace App\Http\Controllers\Admin\Cart\CartProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\CartProduct\StoreRequest;
use App\Http\Resources\Admin\User\UserCart\UserCartResource;
use App\Models\Cart;
use App\Services\Admin\Cart\CartProduct\CartProductCreator;
use App\Services\Admin\Cart\CartProduct\CartProductService;

class StoreController extends Controller
{
    private $cartProductCreator;

    public function __construct(CartProductCreator $cartProductCreator)
    {
        $this->cartProductCreator = $cartProductCreator;
    }


    public function __invoke(Cart $cart, StoreRequest $request)
    {
        $data = $request->validated();
        $cart = $this->cartProductCreator->addProductToCart($data['product'], $cart);

        $cart->load('products.activationKeys');

        return response()->json([
            'message' => 'Продукт добавлен в корзину',
            'data'    => new UserCartResource($cart),
        ], 200);
    }
}
