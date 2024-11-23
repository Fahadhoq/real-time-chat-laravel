<?php

namespace App\Providers;
use App\Services\CustomService;
use Illuminate\Support\ServiceProvider;
use App\Services\NotificationServiceInterface;
use App\Services\EmailNotificationService;
use App\Services\SmsNotificationService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomService::class, function ($app) {
            return new CustomService();
        });

        $this->app->bind(NotificationServiceInterface::class, function ($app) {
            // Use SMS or Email based on a condition
            if (config('app.notification.driver') === 'sms') {
                return new SmsNotificationService();
            }else{
                return new EmailNotificationService();
            }
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
