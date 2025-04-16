<?php

namespace App\Providers;

use App\Contracts\ActivationKeyRepositoryInterface;
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
    }
}
