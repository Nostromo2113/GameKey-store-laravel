<?php

namespace App\Http\Controllers\Admin\Product\ProductActivationKey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActivationKey\StoreRequest;
use App\Http\Resources\Admin\Product\ActivationKeyProduct\ProductActivationKeyResource;
use App\Models\ActivationKey;
use App\Models\Product;

class StoreController extends Controller
{
    public function __invoke(Product $product, StoreRequest $request)
    {
        // policy
        $this->authorize('create', ActivationKey::class);

        $data = $request->validated()['activation_key'];

        $key = $product->activationKeys()->create([
            'key' => $data['key'],
        ]);

        return response()->json([
            'message' => 'Activation key created successfully',
            'data' => new ProductActivationKeyResource($key)
        ], 201);
    }
}
