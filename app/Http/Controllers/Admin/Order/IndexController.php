<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Query\Sort\OrderSort;
use App\Models\Order;

class IndexController extends Controller
{
    public function __invoke()
    {
        $filter = app(OrderSort::class, [ 'queryParams' => ['createdAt' => 'desc']]);

        return Order::filter($filter)->paginate(10);
    }
}
