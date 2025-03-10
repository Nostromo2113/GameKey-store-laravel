<?php

namespace App\Services\Admin\Cart\CartProduct;

use App\Models\Cart;
use App\Models\Product;

class CartProductService
{
    private $cartProductCreateService;
    private $cartProductUpdateService;
    private $cartProductDestroyService;

    public function __construct(
        CartProductCreateService $cartProductCreateService,
        CartProductUpdateService $cartProductUpdateService,
        CartProductDestroyService $cartProductDestroyService
    )
    {
        $this->cartProductCreateService = $cartProductCreateService;
        $this->cartProductUpdateService = $cartProductUpdateService;
        $this->cartProductDestroyService = $cartProductDestroyService;
    }

    public function store(array $data, Cart $cart) :array
    {
        $response = $this->cartProductCreateService->storeProductInCart($data, $cart);
        return $response;
    }

    public function update(array $data, Cart $cart, Product $product) :Cart
    {
        $updatedCart = $this->cartProductUpdateService->updateProductQuantityInCart($data, $cart, $product);
        return $updatedCart;
    }

    public function destroy(Cart $cart, int $productId) :void
    {
        $this->cartProductDestroyService->deleteProductInCart($cart, $productId);
    }

    public function destroyAll(int $userId) :void
    {
        $this->cartProductDestroyService->deleteAllProductsInCart($userId);
    }
}
