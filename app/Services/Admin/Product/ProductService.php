<?php

namespace App\Services\Admin\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class ProductService
{
    private $productStoreService;
    private $productDestroyService;
    private $productUpdateService;

    public function __construct(
        ProductStoreService $productStoreService,
        ProductUpdateService $productUpdateService,
        ProductDestroyService $productDestroyService)
    {
        $this->productStoreService = $productStoreService;
        $this->productUpdateService = $productUpdateService;
        $this->productDestroyService = $productDestroyService;
    }

    public function store(array $data): Product
    {
        if (Gate::denies('create')) {
            abort(403, 'У вас нет прав на обновление этого продукта');
        }
        $product = $this->productStoreService->storeProduct($data);
        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        if (Gate::denies('update', $product)) {
            abort(403, 'У вас нет прав на обновление этого продукта');
        }
        $product = $this->productUpdateService->updateProduct($product, $data);
        return $product;
    }

    public function destroy(Product $product): void
    {
        if (Gate::denies('delete', $product)) {
            abort(403, 'У вас нет прав на обновление этого продукта');
        }
        $this->productDestroyService->destroyProduct($product);
    }
}
