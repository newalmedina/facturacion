<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use App\Observers\BrandObserver;
use App\Observers\CustomerObserver;
use App\Observers\ItemObserver;
use App\Observers\SupplierObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            class_alias(\Barryvdh\Debugbar\Facade::class, 'Debugbar');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        User::observe(UserObserver::class);
        Brand::observe(BrandObserver::class);
        Supplier::observe(SupplierObserver::class);
        Customer::observe(CustomerObserver::class);
        Item::observe(ItemObserver::class);
    }
}
