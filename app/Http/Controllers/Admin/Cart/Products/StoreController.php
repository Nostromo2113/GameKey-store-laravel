<?php

namespace App\Http\Controllers\Admin\Cart\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\UpdateRequest;
use App\Models\Cart;
use App\Services\Admin\Cart\CartProduct\CartProductService;

class StoreController extends Controller
{
    private $cartProductService;
    public function __construct(CartProductService $cartProductService)
    {
        $this->cartProductService = $cartProductService;
    }


    public function __invoke(Cart $cart, UpdateRequest $request)
    {
        $data = $request->validated();
            $result = $this->cartProductService->store($data, $cart);

            return response()->json([
                'message' => $result['message'],
                'cart' => $result['cart'] ?? null,
            ], $result['success'] ? 200 : 400);
    }
}
