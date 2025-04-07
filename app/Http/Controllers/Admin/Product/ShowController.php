<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Product\ProductFullResource;
use App\Models\Product;

class ShowController extends Controller
{
    public function __invoke(Product $product)
    {
        return new ProductFullResource($product);
    }
}
