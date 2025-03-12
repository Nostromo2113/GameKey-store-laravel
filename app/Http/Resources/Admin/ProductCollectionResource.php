<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => ProductResource::collection($this->collection),
            'current_page' => $this->resource->currentPage(),
            'last_page' => $this->resource->lastPage(),
            'per_page' => $this->resource->perPage(),
            'total' => $this->resource->total(),
            'next_page_url' => $this->resource->nextPageUrl(),
            'prev_page_url' => $this->resource->previousPageUrl(),
        ];
    }
}
