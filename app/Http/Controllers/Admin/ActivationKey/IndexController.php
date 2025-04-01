<?php

namespace App\Http\Controllers\Admin\ActivationKey;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Product\ActivationKeyProduct\ProductActivationKeyCollectionResource;
use App\Models\ActivationKey;

class IndexController extends Controller
{
    public function __invoke()
    {

        $keys = ActivationKey::with('product')->paginate(20);

        return new ProductActivationKeyCollectionResource($keys);
    }
}
