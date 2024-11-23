<?php

// app/Services/SmsNotificationService.php
namespace App\Services;

class SmsNotificationService implements NotificationServiceInterface
{
    public function send(string $message, string $recipient): string
    {
        // SMS sending logic here
        return "SMS sent to $recipient: $message";
    }
}

