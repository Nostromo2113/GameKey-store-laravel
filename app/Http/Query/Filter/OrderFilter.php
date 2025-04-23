<?php

namespace App\Http\Query\Filter;

use App\Http\Query\AbstractQuery;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractQuery
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
