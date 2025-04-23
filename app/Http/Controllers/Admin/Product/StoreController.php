<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Resources\Admin\Product\ProductResource;
use App\Models\Product;
use App\Services\Admin\Product\ProductCreator;
use App\Services\Admin\Product\ProductService;

class StoreController extends Controller
{
    private $productCreator;

    public function __construct(ProductCreator $productCreator)
    {
        $this->productCreator = $productCreator;
    }
    public function __invoke(StoreRequest $request)
    {
        $this->authorize('create', Product::class);
        $data = $request->validated();

        $product = $this->productCreator->storeProduct($data['product']);

        $product->load('category', 'technicalRequirements', 'genres', 'activationKeys');

        return response()->json([
            'message' => 'Продукт создан',
            'data'    => new ProductResource($product)
        ]);
    }
}
