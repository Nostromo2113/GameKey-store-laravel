<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_price' => $this->products->reduce(function ($sum, $product) {
                return $sum + ($product->price *  $product->pivot->quantity);
            }, 0),
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'publisher' => $product->publisher,
                    'release_date' => $product->release_date,
                    'preview_image' => $product->preview_image,
                    'price' => $product->price,
                    'total_price' => $product->pivot->price,
                    'quantity_cart' => $product->pivot->quantity,
                    'quantity_store' => $product->activationKeys->where('order_product_id', null)->count(),
                ];
            })
        ];
    }
}
