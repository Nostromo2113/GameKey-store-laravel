<?php

namespace App\Http\Filters\Order;

use App\Http\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class OrderSort extends AbstractFilter
{
    public const CREATED_AT = 'createdAt';
    protected function getCallbacks(): array
    {
        return [
            self::CREATED_AT => [$this, 'createdAt'],
        ];
    }

    public function createdAt(Builder $builder, string $value): void
    {

        $builder->orderBy('created_at', $value);
    }

}
