<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RiskNotificationService;

class RiakServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(RiskNotificationService::class, function ($app) {
            return new RiskNotificationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
