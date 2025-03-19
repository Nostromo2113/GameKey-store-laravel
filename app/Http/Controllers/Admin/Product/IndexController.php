<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Filters\Product\ProductFilter;
use App\Http\Requests\Admin\Product\FilterRequest;
use App\Http\Requests\Admin\Product\IndexRequest;
use App\Http\Resources\Admin\Product\ProductCollectionResource;
use App\Models\Product;

class IndexController extends Controller
{

    public function __invoke(FilterRequest $filterRequest)
    {
        $data = $filterRequest->validated();

        $filter = app()->make(ProductFilter::class, [ 'queryParams' => array_filter($data, fn($value) => $value !== null && $value !== '')]);

        $productsQuery = Product::filter($filter);

        $products = $productsQuery->paginate(8);

        return new ProductCollectionResource($products);
    }
}
