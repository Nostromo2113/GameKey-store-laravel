<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Http\Resources\Admin\Product\ProductResource;
use App\Models\Product;
use App\Services\Admin\Product\ProductService;


class UpdateController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function __invoke(UpdateRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validated();

        $product = $this->productService->update($product, $data['product']);

        $product->load('category', 'technicalRequirements', 'genres');

        return response()->json([
            'message' => 'Продукт успешно обновлен',
            'data' => new ProductResource($product),
        ], 200);
    }
}
