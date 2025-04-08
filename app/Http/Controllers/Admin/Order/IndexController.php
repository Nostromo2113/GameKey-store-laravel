<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Filters\Product\OrderSort;
use App\Models\Order;

class IndexController extends Controller
{
    public function __invoke()
    {
        $filter = app()->make(OrderSort::class, [ 'queryParams' => ['sort' => 'createdAt:desc']]);
        return Order::filter($filter)->get();
    }
}
