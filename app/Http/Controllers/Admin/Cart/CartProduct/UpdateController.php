<?php

namespace App\Http\Controllers\Admin\Cart\CartProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\CartProduct\UpdateRequest;
use App\Http\Resources\Admin\User\UserCart\UserCartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Services\Admin\Cart\CartProduct\CartProductService;

class UpdateController extends Controller
{
    private $cartProductService;

    public function __construct(CartProductService $cartProductService)
    {
        $this->cartProductService = $cartProductService;
    }

    public function __invoke(UpdateRequest $request, Cart $cart, Product $product)
    {
        $data = $request->validated();

        $cart = $this->cartProductService->update($data['product'], $cart, $product);

        $cart->load('products.activationKeys');

        return response()->json([
            'message' => 'Продукт в корзине успешно обновлен',
            'data' => new UserCartResource($cart)
        ], 200);
    }
}
