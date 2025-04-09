<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Product;

class DestroyController extends Controller
{
    public function __invoke(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json('Продукт успешно удален', 200);
    }
}
