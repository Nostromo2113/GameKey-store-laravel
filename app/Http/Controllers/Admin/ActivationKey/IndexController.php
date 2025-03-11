<?php

namespace App\Http\Controllers\Admin\ActivationKey;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\ActivationKey\ActivationKeyIndexRequest;
use App\Http\Resources\Admin\ActivationKeyCollectionResource;
use App\Models\ActivationKey;
use App\Models\Product;

class IndexController extends Controller
{
    public function __invoke(ActivationKeyIndexRequest $request)
    {
        $data = $request->validated();
        if (isset($data['product_id'])) {
            $product = Product::with('activationKeys.product')->findOrFail($data['product_id']);
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }
            $keys = $product->activationKeys()->paginate(15);
        } else {
            $keys = ActivationKey::with('product')->paginate(15);
        }
        $keys = new ActivationKeyCollectionResource($keys);

        return response()->json($keys, 200);
    }
}
