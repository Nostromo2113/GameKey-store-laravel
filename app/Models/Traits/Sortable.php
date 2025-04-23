<?php

namespace App\Models\Traits;

use App\Http\Query\QueryInterface;
use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function scopeSort(Builder $builder, QueryInterface $filter)
    {
        $filter->apply($builder);

        return $builder;
    }
}
