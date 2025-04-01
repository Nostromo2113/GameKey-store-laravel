<?php

namespace App\Http\Resources\Admin\Product\ActivationKeyProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductActivationKeyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'product_id' => $this->product_id,
            'product_title' => $this->product->title ?? null,
            'reserved' => $this->order_product_id !== null,
        ];
    }
}
