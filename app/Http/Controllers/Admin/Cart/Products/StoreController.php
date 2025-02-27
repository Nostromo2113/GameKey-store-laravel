<?php

namespace App\Http\Controllers\Admin\Cart\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\UpdateRequest;
use App\Models\Cart;
use App\Services\Admin\Cart\CartProductStoreService;

class StoreController extends Controller
{
    public function __construct(CartProductStoreService $cartProductService)
    {
        $this->cartProductService = $cartProductService;
    }


    public function __invoke(Cart $cart, UpdateRequest $request)
    {
        $data = $request->validated();
        try {
            $result = $this->cartProductService->store($data, $cart);

            return response()->json([
                'message' => $result['message'],
                'cart' => $result['cart'] ?? null,
            ], $result['success'] ? 200 : 400);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

    }
}
