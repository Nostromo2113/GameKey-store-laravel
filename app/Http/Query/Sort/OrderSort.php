<?php

namespace App\Http\Filters\Order;

use App\Http\Filters\AbstractQuery;
use Illuminate\Database\Eloquent\Builder;

/**
 * TODO: Перенести в Filter
 */
class OrderSort extends AbstractQuery
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
