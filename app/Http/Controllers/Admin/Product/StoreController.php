<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Resources\Admin\ProductResource;
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

        $data = $request->validated();

        $product = $this->productService->store($data);

        $product->load('category', 'technicalRequirements', 'genres', 'activationKeys');

        return response()->json([
            'message' => 'Продукт создан',
            'data' => new ProductResource($product)
        ]);
    }
}
