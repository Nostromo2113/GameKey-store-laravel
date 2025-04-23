<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

interface QueryInterface
{
    public function apply(Builder $builder);
}
