<?php

namespace App\Http\Resources\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFullResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(
            (new ProductResource($this))->toArray($request),
            [
                'technical_requirements' => $this->technicalRequirements,
                'category'               => $this->category,
                'genres'                 => $this->genres,
            ]
        );
    }
}
