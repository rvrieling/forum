<?php

namespace App\Broadcasting;

use App\Models\User;

class ChatChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @return array|bool
     */
    public function join($token, $roomId)
    {
        $user = User::query()
            ->whereHas('api_token', function ($query) use ($token) {
                $query->where('api_token', $token->api_token);
            })
            ->select(['id', 'user_name'])
            ->first();
            
        return $user;
    }
}
