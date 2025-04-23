<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Http\Resources\Admin\Product\ProductFullResource;
use App\Models\Product;
use App\Services\Admin\Product\ProductService;
use App\Services\Admin\Product\ProductUpdater;

class UpdateController extends Controller
{
    private $productUpdater;

    public function __construct(ProductUpdater $productUpdater)
    {
        $this->productUpdater = $productUpdater;
    }

    public function __invoke(UpdateRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validated();

        $product = $this->productUpdater->updateProduct($product, $data['product']);

        $product->load('category', 'technicalRequirements', 'genres');

        return response()->json([
            'message' => 'Продукт успешно обновлен',
            'data'    => new ProductFullResource($product),
        ], 200);
    }
}
