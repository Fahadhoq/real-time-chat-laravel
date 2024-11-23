<?php

// app/Services/EmailNotificationService.php
namespace App\Services;

class EmailNotificationService implements NotificationServiceInterface
{
    public function send(string $message, string $recipient): string
    {
        // Email sending logic here
        return "Email sent to $recipient: $message";
    }
}

