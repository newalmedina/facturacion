<?php

namespace App\Providers;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class FilamentAssetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        /*FilamentAsset::register([
            Js::make('custom-tailwind', asset('js/filament/tailwind/tailwind.js')),
        ]);*/
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
