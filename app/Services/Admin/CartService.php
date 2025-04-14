<?php

namespace App\Services\Admin;

use App\Models\Cart;
use App\Models\Product;

class CartService
{
    /**
     * Создает корзину. Вызывается при регистрации пользователя
     *
     * @param int $userId id пользователя, для которого создаем корзину
     * @return Cart $cart возвращаем корзину
     * @throws \Exception Ошибка.
     */
    public function storeCart(int $userId): Cart
    {
        try {
            $cart = Cart::create([
                'user_id'     => $userId,
                'total_price' => 0
            ]);

            return $cart;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    /**
     * Добавляет продукт в корзину.
     *
     * @param array $data Валидированные данные запроса.
     * @param Cart $cart Корзина, в которую добавляется продукт.
     * @return array Возвращает массив с результатом.
     * @throws \Exception Ошибка.
     */
    public function addProductToCart(array $data, Cart $cart): array
    {
        try {
            $productId  = $data['product_id'];
            $quantity   = $data['quantity'];
            $totalPrice = $quantity * $data['price'];

            $productExist = $cart->products()->where('cart_products.product_id', $productId)->exists();

            if (!$productExist) {
                $cart->products()->attach($productId, ['quantity' => $quantity, 'price' => $totalPrice]);

                return [
                    'success' => true,
                    'message' => 'Product added to cart',
                    'cart'    => $cart->load('products'),
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Product already exists in cart',
                ];
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * Обновляет количество продуктов в корзине.
     *
     * @param array $data Входные данные, включая новое количество.
     * @param Cart $cart Корзина, которую нужно обновить.
     * @param Product $product Продукт, количество которого обновляется.
     * @return Cart Возвращает обновленную корзину.
     */
    public function updateCartProductQuantity(array $data, Cart $cart, Product $product): Cart
    {
        $newQuantity = $data['quantity'];

        // Получаем количество доступных ключей активации для продукта
        $availableKeys = $product->activationKeys()->where('order_product_id', null)->count();

        // Находим продукт в корзине
        $cartProduct = $cart->products()->where('product_id', $product->id)->first();
        // Получаем кол-во продукта в корзине актуальное на данный момент
        $oldQuantity = $cartProduct->pivot->quantity;

        // Если доступных ключей меньше, чем текущее количество, обновляем до доступного количества
        if ($availableKeys < $oldQuantity) {
            // Обновляем количество в корзине до доступных ключей
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $availableKeys]);
        } elseif ($newQuantity !== $oldQuantity) {
            // Иначе обновляем до нового количества, если оно изменилось
            $cart->products()->updateExistingPivot($product->id, ['quantity' => $newQuantity]);
        }

        $cart->load('products');

        return $cart;
    }




    /**
     * Удаляет продукты из корзины. Вызывается про оформлении заказа на основе корзины
     *
     * @param int $userId id пользователя корзины
     * @return void
     * @throws \Exception Ошибка.
     */
    public function clearCart(int $userId): void
    {
        try {
            $cart = Cart::where('user_id', $userId)->firstOrFail();
            $cart->products()->detach();
        } catch (\Exception $e) {
            throw new \Exception("Ошибка при очистке корзины: " . $e->getMessage());
        }
    }

}
