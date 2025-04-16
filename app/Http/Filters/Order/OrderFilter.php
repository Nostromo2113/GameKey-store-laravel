<?php

namespace App\Http\Filters\Order;

use App\Http\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
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
