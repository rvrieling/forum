<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotifyMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queue = 'Forum:local:default';

    public $room;
    public $message;
    public $userID;
    public $user_name;
    public $user_image;
    public $updated_at;
    public $room_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id, $message, $room, $user)
    {
        $this->user_name  = $user->user_name;
        $this->user_image = $user->id;
        $this->room       = $room->name;
        $this->message    = $message;
        $this->userID     = $user_id->id;
        $this->updated_at = $room->updated_at;
        $this->room_id    = $room->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.notification.' . $this->userID);
    }

    public function broadcastAs()
    {
        return 'new.user.notification.' . $this->userID;
    }

    public function broadcastWith()
    {
        return [
            'unread_message' => 'true',
            'room_name'      => $this->room,
            'user_name'      => $this->user_name,
            'user_image'     => $this->user_image,
            'message'        => $this->message,
            'updated_at'     => $this->updated_at,
            'room_id'        => $this->room_id,
        ];
    }
}
