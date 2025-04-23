<?php

namespace App\Http\Filters\Order;

use App\Http\Filters\AbstractQuery;
use Illuminate\Database\Eloquent\Builder;

class OrderQuery extends AbstractQuery
{
    public const ORDER_NUMBER = 'order_number';


    protected function getCallbacks(): array
    {
        return [
            self::ORDER_NUMBER => [$this, 'orderNumber'],
        ];
    }

    public function orderNumber(Builder $builder, int $value): void
    {
        $builder->where('order_number', $value);
    }
}
