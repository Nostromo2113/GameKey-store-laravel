<?php

namespace App\Models\Traits;

use App\Http\Query\QueryInterface;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * @param Builder $builder
     * @param QueryInterface $filter
     *
     * @return Builder
     */
    public function scopeFilter(Builder $builder, QueryInterface $filter)
    {
        $filter->apply($builder);

        return $builder;
    }
}
