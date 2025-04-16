<?php

namespace App\Http\Resources\Admin\Order\OrderProduct;

use App\Http\Resources\Admin\ActivationKey\ActivationKeyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->product->id,
            'title'           => $this->product->title,
            'description'     => $this->product->description,
            'publisher'       => $this->product->publisher,
            'release_date'    => $this->product->release_date,
            'preview_image'   => $this->product->preview_image,
            'price'           => $this->product->price,
            'category'        => $this->whenLoaded('product.category'),
            'is_published'    => $this->product->is_published,
            'quantity'        => $this->quantity,
            'activation_keys' => ActivationKeyResource::collection(
                $this->whenLoaded('activationKeys')
            ),
        ];
    }
}
