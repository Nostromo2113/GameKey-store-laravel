<?php

namespace App\Http\Controllers\Admin\Cart\CartProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\CartProduct\UpdateRequest;
use App\Http\Resources\Admin\User\UserCart\UserCartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Services\Admin\Cart\CartProduct\CartProductService;
use App\Services\Admin\Cart\CartProduct\CartProductUpdater;

class UpdateController extends Controller
{
    private $cartProductUpdater;

    public function __construct(CartProductUpdater $cartProductUpdater)
    {
        $this->cartProductUpdater = $cartProductUpdater;
    }

    public function __invoke(UpdateRequest $request, Cart $cart, Product $product)
    {
        $data = $request->validated();

        $cart = $this->cartProductUpdater->updateProductQuantityInCart($data['product'], $cart, $product);

        $cart->load('products.activationKeys');

        return response()->json([
            'message' => 'Продукт в корзине успешно обновлен',
            'data'    => new UserCartResource($cart)
        ], 200);
    }
}
