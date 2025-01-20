<?php

namespace App\Http\Resources\Admin;

use App\Models\ActivationKey;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'user_id' => $this->user_id,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'user' => $this->user,
            'order_date' => $this->created_at,
            'products' => $this->OrderProducts->map(function ($orderProduct) {
                $activationKeys = $orderProduct->activationKeys;
                $product = $orderProduct->product;
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'description' => $product->description,
                    'publisher' => $product->publisher,
                    'release_date' => $product->release_date,
                    'preview_image' => $product->preview_image,
                    'price' => $product->price,
                    'amount' => $product->amount,
                    'category' => $product->category,
                    'is_published' => $product->is_published,
                    'activation_keys' => $activationKeys->map(function($key) {
                      return [
                        'key' => $key->key
                      ];
                    }),
                ];
            }),
        ];
    }
//
//return [
//    'id' => $this->id,
//    'order_number' => $this->order_number,
//    'user_id' => $this->user_id,
//    'total_price' => $this->total_price,
//    'status' => $this->status,
//    'user' => $this->user,
//    'order_date' => $this->created_at,
//    'products' => $this->products->groupBy('id')->map(function ($groupedProducts) {
//        $firstProduct = $groupedProducts->first();
//
//        return [
//            'id' => $firstProduct->id,
//            'title' => $firstProduct->title,
//            'description' => $firstProduct->description,
//            'publisher' => $firstProduct->publisher,
//            'release_date' => $firstProduct->release_date,
//            'preview_image' => $firstProduct->preview_image,
//            'price' => $firstProduct->price,
//            'amount' => $firstProduct->amount,
//            'category' => $firstProduct->category,
//            'is_published' => $firstProduct->is_published,
//            'activation_keys' => $groupedProducts->map(function ($product) {
//                return [
//                    'activation_key' => ActivationKey::find($product->pivot->activation_key_id)->key,
//                    'activation_key_id' => $product->pivot->activation_key_id,
//                ];
//            }),
//        ];
//    })->values(),
//];
}
