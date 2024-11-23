<?php

// app/Facades/Notification.php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RiskFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        // Return the name of the service in the container
        return \App\Services\RiskNotificationService::class;
    }
}
