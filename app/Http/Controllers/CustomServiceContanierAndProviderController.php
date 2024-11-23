<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Services\CustomService;
use App\Services\NotificationServiceInterface;
use App\Models\User;
use Risk;
class CustomServiceContanierAndProviderController extends Controller
{
    private $notificationService;

    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function sendNotification()
    {
        $message = "Hello, this is a test notification!";
        $recipient = "user@example.com";

        return $this->notificationService->send($message, $recipient);
    }


    public function serviceContanier(CustomService $service)
    {
        return $service->handle();
    }


    //facade
    public function createUser(Request $request)
    {
        $message = "New user has been created!";
        $notificationResponse = Risk::sendNotification($message);

        return response()->json(['message' => $notificationResponse]);
    }


}
