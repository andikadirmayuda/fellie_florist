<?php

namespace App\Providers;

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
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);

        // Register collection macro for filtering non-empty prices
        \Illuminate\Support\Collection::macro('filterPrices', function () {
            return $this->filter(function ($price) {
                return isset($price['price']) && $price['price'] !== null && $price['price'] !== '';
            })->values();
        });

        //
    }
}
