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
        // Register Services
        $this->app->singleton(\App\Services\DashboardService::class);
        $this->app->singleton(\App\Services\BookingService::class);
        
        // Register Repositories
        $this->app->singleton(\App\Repositories\BookingRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
