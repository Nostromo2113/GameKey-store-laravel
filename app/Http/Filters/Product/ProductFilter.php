<?php

namespace App\Http\Filters\Product;

use App\Http\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends AbstractFilter
{
    public const TITLE = 'title';
    public const IS_PUBLISHED = 'is_published';
    public const CATEGORY_ID = 'category_id';
    public const PRICE_SORT = 'price_sort';

    protected function getCallbacks(): array
    {
        return [
            self::TITLE => [$this, 'title'],
            self::IS_PUBLISHED => [$this, 'isPublished'],
            self::CATEGORY_ID => [$this, 'categoryId'],
            self::PRICE_SORT => [$this, 'priceSort'],
        ];
    }

    public function title(Builder $builder, string $value): void
    {

        $builder->where('title', 'like', '%' . $value . '%');
    }

    public function categoryId(Builder $builder, int $value): void
    {
        $builder->where('category_id', $value);
    }

    public function isPublished(Builder $builder, $value): void
    {
        $builder->where('is_published', (bool) $value);
    }

    public function priceSort(Builder $builder, $value): void
    {
        $builder->orderBy('price', $value);
    }

}
