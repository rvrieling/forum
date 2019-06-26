<?php

namespace App\Observers;

use App\Models\ImagePost;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;

class PostObserver
{
    public function created(Post $post)
    {
        $users = User::get();

        foreach ($users as $user) {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id
            ]);
        }
    }
    public function deleted(Post $post)
    {
        Like::query()
            ->where('post_id', $post->id)
            ->delete();

        ImagePost::query()
            ->where('post_id', $post->id)
            ->delete();
    }
}
