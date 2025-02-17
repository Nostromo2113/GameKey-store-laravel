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
            'total_price' => $this->total_price,
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'publisher' => $product->publisher,
                    'release_date' => $product->release_date,
                    'preview_image' => $product->preview_image,
                    'price' => $product->price,
                    'quantity' => $product->pivot->quantity
                ];
            })
        ];
    }
}
