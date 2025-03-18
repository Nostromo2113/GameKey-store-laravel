<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use App\Models\TechnicalRequirement;
use App\Services\Admin\Product\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Storage;


class UpdateController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function __invoke(UpdateRequest $request, Product $product)
    {

        $data = $request->validated();

        $product = $this->productService->update($product, $data);

        $product->load('category', 'technicalRequirements', 'genres');

        return response()->json([
            'message' => 'Продукт успешно обновлен',
            'data' => new ProductResource($product),
        ], 200);


    }
}
