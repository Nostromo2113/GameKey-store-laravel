<?php

namespace App\Http\Controllers\Admin\Product\ProductComment;

use App\Http\Controllers\Controller;
use App\Models\Product;

class IndexController extends Controller
{
    public function __invoke(Product $product)
    {
        $comments = $product->comments;

        return $comments;
    }
}
