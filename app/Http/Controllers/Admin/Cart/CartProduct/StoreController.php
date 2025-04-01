<?php

namespace App\Http\Controllers\Admin\Cart\CartProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\CartProduct\StoreRequest;
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
        $result = $this->cartProductService->store($data['product'], $cart);

        return response()->json([
            'message' => $result['message'],
            'cart' => $result['cart'] ?? null,
        ], $result['success'] ? 200 : 400);
    }
}
