<?php


use App\Broadcasting\ChatChannel;
use App\Broadcasting\NotifyChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.room.{id}', ChatChannel::class);

Broadcast::channel('user.notification.{userId}', NotifyChannel::class);
