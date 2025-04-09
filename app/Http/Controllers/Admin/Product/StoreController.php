<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Resources\Admin\Product\ProductResource;
use App\Models\Product;
use App\Services\Admin\Product\ProductService;

class StoreController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function __invoke(StoreRequest $request)
    {
        $this->authorize('create', Product::class);
        $data = $request->validated();

        $product = $this->productService->store($data['product']);

        $product->load('category', 'technicalRequirements', 'genres', 'activationKeys');

        return response()->json([
            'message' => 'Продукт создан',
            'data' => new ProductResource($product)
        ]);
    }
}
