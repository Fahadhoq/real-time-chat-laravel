<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestData implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messeng;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct($messeng,$user)
    {
        $this->messeng = $messeng;
        $this->user = $user;
    }

    //if need custom-name chanale name
    // public function broadcastAs()
    // {
    //     return 'custom-name';
    // }

    //if need a static msg with data
    // public function broadcastWith()
    // {
    //     return [
    //         'data' => $this->data . ', How are you?'
    //     ];
    // }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        // return [
        //     new PrivateChannel('channel-name'),
        // ];
        
        //Public Channel
        // return new Channel('test-channel');

        //Private Channel
        // return new PrivateChannel('test-PrivateChannel');

        //PresenceChannel 
        return new PresenceChannel('test-PresenceChannel');

    }
}
