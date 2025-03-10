<?php

namespace App\Http\Controllers\Admin\Cart\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\UpdateRequest;
use App\Http\Resources\Admin\CartResource;
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
            $cart = $this->cartProductService->update($data, $cart, $product);
            return response()->json([
                'data' => new CartResource($cart),
                'message' => 'Продукт в корзине успешно обновлен'
            ], 200);
    }
}
