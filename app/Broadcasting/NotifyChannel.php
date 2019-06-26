<?php

namespace App\Broadcasting;

use App\Models\User;

class NotifyChannel
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
    public function join($token, $userID)
    {
        if ($token->user_id == $userID) {
            return ['id' => $userID];
        }
    }
}
