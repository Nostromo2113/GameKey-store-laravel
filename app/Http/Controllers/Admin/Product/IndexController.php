<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\IndexRequest;
use App\Http\Resources\Admin\ProductCollectionResource;
use App\Models\Product;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();

        $productsQuery = Product::query()
            ->with(['activationKeys']); // Предзагрузка связанных ключей

        if (isset($data['query'])) {
            $productsQuery->where('title', 'like', '%' . $data['query'] . '%');
        }

        $products = $productsQuery->get(); // Выполнение запроса

        return ProductCollectionResource::collection($products);
    }
}
