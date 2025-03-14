<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Supplier;
use App\Models\User;
use App\Observers\BrandObserver;
use App\Observers\SupplierObserver;
use App\Observers\UserObserver;
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
        User::observe(UserObserver::class);
        Brand::observe(BrandObserver::class);
        Supplier::observe(SupplierObserver::class);
    }
}
