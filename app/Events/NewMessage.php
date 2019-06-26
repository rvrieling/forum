<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queue = 'Forum:local:default';

    public $room_id;
    public $user;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room_id, $user, $message)
    {
        $this->room_id = $room_id;
        $this->user    = $user;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat.room.' . $this->room_id);
    }

    public function broadcastAs()
    {
        return 'new.message.room.' . $this->room_id;
    }

    public function broadcastWith()
    {
        return [
            'user_name' => $this->user->user_name,
            'user_id'   => $this->user->id,
            'message'   => $this->message,
        ];
    }
}
