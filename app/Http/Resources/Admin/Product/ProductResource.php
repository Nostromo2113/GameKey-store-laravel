<?php

namespace App\Http\Resources\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'publisher'     => $this->publisher,
            'release_date'  => $this->release_date,
            'preview_image' => $this->preview_image,
            'price'         => $this->price,
            'amount'        => $this->activationKeys->whereNull('order_product_id')->count(),
            'is_published'  => $this->is_published,
        ];
    }
}
