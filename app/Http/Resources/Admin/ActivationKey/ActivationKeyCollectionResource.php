<?php

namespace App\Http\Resources\Admin\ActivationKey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ActivationKeyCollectionResource extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => ActivationKeyResource::collection($this->collection),
            'meta' => [
                'current_page'  => $this->currentPage(),
                'last_page'     => $this->lastPage(),
                'per_page'      => $this->perPage(),
                'total'         => $this->total(),
                'next_page_url' => $this->nextPageUrl(),
                'prev_page_url' => $this->previousPageUrl(),
            ],
        ];
    }
}
