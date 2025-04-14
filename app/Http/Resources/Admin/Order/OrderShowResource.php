<?php

namespace App\Http\Resources\Admin\Order;

use App\Http\Resources\Admin\Order\OrderProduct\OrderProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'order_number'   => $this->order_number,
            'user_id'        => $this->user_id,
            'total_price'    => $this->total_price,
            'status'         => $this->status,
            'user'           => $this->whenLoaded('user'),
            'order_date'     => $this->created_at,
            'order_products' => OrderProductResource::collection(
                $this->whenLoaded('orderProducts')
            ),
        ];
    }
}
