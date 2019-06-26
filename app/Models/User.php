<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function api_token()
    {
        return $this->hasMany(ApiToken::class);
    }

    public function categorys()
    {
        return $this->hasMany(Category::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function subs()
    {
        return $this->hasMany(Sub::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function userSettings()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function chatrooms()
    {
        return $this->hasMany(ChatroomUser::class);
    }

    public function chat_messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
