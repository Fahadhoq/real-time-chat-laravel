<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function notificationList(Request $request){
        $user = User::find(Auth::user()->id); // Replace 1 with the actual user ID
       
        // Fetch all notifications
        $data['notifications'] = $user->notifications;

        // // Fetch only unread notifications
        // $data['unreadNotifications'] = $user->unreadNotifications;

        // // Fetch only read notifications
        // $data['readNotifications'] = $user->readNotifications;

        return view('notification.notifications', $data);
    }    
   
    public function oderShip(){
        $user = User::find(Auth::user()->id); // Replace with the desired user ID

        $data = [
            'title' => 'New Order Received',
            'message' => 'You have a new order from customer John Doe.'
        ];

        $user->notify(new DatabaseNotification($data));

        
        // Fetch all notifications
        $data['notifications'] = $user->notifications;

        // // Fetch only unread notifications
        // $data['unreadNotifications'] = $user->unreadNotifications;

        // // Fetch only read notifications
        // $data['readNotifications'] = $user->readNotifications;

        return view('notification.notifications', $data);
    }  

}
