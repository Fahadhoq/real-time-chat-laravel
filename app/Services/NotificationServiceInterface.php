<?php

// app/Services/NotificationServiceInterface.php
namespace App\Services;

interface NotificationServiceInterface
{
    public function send(string $message, string $recipient): string;
}
