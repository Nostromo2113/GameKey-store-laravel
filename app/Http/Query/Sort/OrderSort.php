<?php

namespace App\Http\Query\Sort;

use App\Http\Query\AbstractQuery;
use Illuminate\Database\Eloquent\Builder;

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
