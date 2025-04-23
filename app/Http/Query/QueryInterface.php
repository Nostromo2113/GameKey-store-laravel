<?php

namespace App\Http\Query;

use Illuminate\Database\Eloquent\Builder;

interface QueryInterface
{
    public function apply(Builder $builder);
}
