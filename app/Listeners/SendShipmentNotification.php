<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;

class SendShipmentNotification
{
    /**
     * Create the event listener. 
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderShipped $event): void
    {
        $user = User::find($event->userId);

        echo "<script>alert('{$user->name} order shipped');</script>";
    }
}
