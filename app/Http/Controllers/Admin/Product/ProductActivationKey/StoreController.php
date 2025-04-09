<?php

namespace App\Http\Controllers\Admin\Product\ProductActivationKey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActivationKey\StoreRequest;
use App\Http\Resources\Admin\ActivationKey\ActivationKeyResource;
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
            'message' => 'Ключ активации создан',
            'data' => new ActivationKeyResource($key)
        ], 201);
    }
}
