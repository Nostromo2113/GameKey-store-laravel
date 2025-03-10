<?php

namespace App\Http\Resources\Admin;

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
                    'quantity' => $orderProduct->quantity,
                    'activation_keys' => $this->status === 'completed'
                        ? $orderProduct->activationKeys()->withTrashed()->get()->map(function ($key) {
                            return [
                                'key' => $key->key,
                                'deleted_at' => $key->deleted_at, // Показываем, что ключ удален
                            ];
                        })
                        : $orderProduct->activationKeys->map(function ($key) {
                            return [
                                'key' => $key->key,
                            ];
                        }),
                ];
            }),
        ];
    }
}
