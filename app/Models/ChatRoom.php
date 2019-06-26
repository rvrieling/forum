<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'most_recent_message',
        'most_recent_user'
    ];

    public function users()
    {
        return $this->hasMany(ChatroomUser::class);
    }

    public function chat_messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
