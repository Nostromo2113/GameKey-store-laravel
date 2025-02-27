<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\StoreRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\Admin\Cart\CartClearService;
use App\Services\Admin\OrderUpdateService;


class StoreController extends Controller
{

    public function __construct(CartClearService $cartClearService)
    {
        $this->cartClearService = $cartClearService;
    }
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $userId = $data['user_id'];
//        $finalPrice = $this->calculateFinalPrice($data);
//        return $finalPrice;


        $order = Order::create([
            'user_id' => $userId,
            'total_price' => 0,
            'status' => 'pending',
            'order_number' => $this->generateOrderNumber(5),
        ]);

        $this->cartClearService->clearCart($userId);

        if($request->filled('order_products')){
            app(OrderUpdateService::class)->update($order, $data);
        }
        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);

    }
    private function generateOrderNumber($length = 5)
    {
        do {
            $orderNumber = '';
            for ($i = 0; $i < $length; $i++) {
                $orderNumber .= random_int(0, 9);
            }
            $exists = Order::where('order_number', $orderNumber)->exists();
        } while ($exists);

        return $orderNumber;
    }



    private function calculateFinalPrice($data)
    {
        if (empty($data['order_products'])) {
            return 0;
        }

        $orderProducts = $data['order_products'];
        $orderProductsIds = array_column($data['order_products'], 'id');

        $products = Product::whereIn('id', $orderProductsIds)->get();
        $finalPrice = 0;
        foreach($orderProducts as $orderProduct) {
           $product = $products->find($orderProduct['id']);
            if ($product) {
                $finalPrice += $orderProduct['quantity'] * $product->price;
            }
        }
        return $finalPrice;
    }
}
