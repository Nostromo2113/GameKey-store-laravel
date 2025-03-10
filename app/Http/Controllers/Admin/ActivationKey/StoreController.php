<?php

namespace App\Http\Controllers\Admin\ActivationKey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActivationKey\ActivationKeyStoreRequest;
use App\Models\ActivationKey;
use App\Models\Product;

class StoreController extends Controller
{
    public function __invoke(ActivationKeyStoreRequest $request)
    {
        $data = $request->validated()['activation_key'];
        $key = ActivationKey::create([
            'key' => $data['key'],
            'product_id' => $data['product_id']
        ]);

        return response()->json([
            'message' => 'Activation key created successfully',
            'data' => $key
        ], 201);
    }
}
