<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Product;

class DestroyController extends Controller
{
    public function __invoke(Product $product)
    {
        $product->delete();
        return response()->json('Product removed', 200);
    }
}
