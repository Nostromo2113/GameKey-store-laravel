<?php

namespace App\Services\Admin\Product;

use App\Models\Product;

class ProductService
{
    private $productStoreService;
    private $productDestroyService;
    private $productUpdateService;

    public function __construct(
        ProductStoreService $productStoreService,
        ProductUpdateService $productUpdateService,
        ProductDestroyService $productDestroyService
    ) {
        $this->productStoreService   = $productStoreService;
        $this->productUpdateService  = $productUpdateService;
        $this->productDestroyService = $productDestroyService;
    }

    public function store(array $data): Product
    {
        $product = $this->productStoreService->storeProduct($data);

        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        $product = $this->productUpdateService->updateProduct($product, $data);

        return $product;
    }

    public function destroy(Product $product): void
    {
        $this->productDestroyService->destroyProduct($product);
    }
}
