<?php

namespace App\Http\Controllers\Admin\Product\ProductActivationKey;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Product\ActivationKeyProduct\ProductActivationKeyCollectionResource;
use App\Models\Product;

class IndexController extends Controller
{
    public function __invoke(Product $product)
    {
        $keys = $product->activationKeys()->with('product')->paginate(20);


        return new ProductActivationKeyCollectionResource($keys);
    }
}

