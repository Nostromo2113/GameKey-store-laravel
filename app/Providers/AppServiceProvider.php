<?php

namespace App\Providers;

use App\Contracts\ActivationKeyRepositoryInterface;
use App\Http\Query\Filter\OrderFilter;
use App\Http\Query\Filter\ProductFilter;
use App\Http\Query\Sort\OrderSort;
use App\Repositories\ActivationKeyRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(ActivationKeyRepositoryInterface::class, ActivationKeyRepository::class);
        $this->app->bind(ProductFilter::class, function ($app, $params) {
            return new ProductFilter($params['queryParams'] ?? []);
        });
        $this->app->bind(OrderSort::class, function ($app, array $params) {
            return new OrderSort($params['queryParams'] ?? []);
        });
        $this->app->bind(OrderFilter::class, function ($app, array $params) {
            $filteredParams = array_filter(
                $params['queryParams'] ?? [],
                fn ($value) => $value !== null && $value !== ''
            );
            return new OrderFilter($filteredParams);
        });
    }
}
