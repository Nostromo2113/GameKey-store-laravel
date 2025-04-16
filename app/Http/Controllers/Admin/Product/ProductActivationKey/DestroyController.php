<?php

namespace App\Http\Controllers\Admin\Product\ProductActivationKey;

use App\Http\Controllers\Controller;
use App\Models\ActivationKey;
use App\Models\Product;

class DestroyController extends Controller
{
    public function __invoke(Product $product, ActivationKey $activationKey)
    {
        // policy
        $this->authorize('delete', $activationKey);

        $activationKey->delete();

        return response()->json('Activation key removed', 200);
    }
}
