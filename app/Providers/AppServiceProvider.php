<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
