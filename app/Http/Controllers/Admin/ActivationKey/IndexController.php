<?php

namespace App\Http\Controllers\Admin\ActivationKey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\IndexRequest;
use App\Models\Cart;
use App\Models\Product;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $product = Product::findOrFail($data['id']);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $keys = $product->activationKeys;

        return response()->json($keys, 200);
    }
}
