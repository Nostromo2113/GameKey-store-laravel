<?php

namespace App\Http\Controllers\Admin\ActivationKey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActivationKey\StoreRequest;
use App\Models\ActivationKey;
use App\Models\Product;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $key = ActivationKey::create([
            'key' => $data['key'],
            'product_id' => $data['id']
        ]);

        return response()->json([
            'message' => 'Activation key created successfully',
            'data' => $key
        ], 201);
    }
}
