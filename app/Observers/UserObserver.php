<?php

namespace App\Observers;

use App\Models\Address;
use App\Models\Category;
use App\Models\Like;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Sub;
use App\Models\User;
use App\Models\UserSetting;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $categories = Category::get();

        foreach ($categories as $category) {
            Sub::create([
                'category_id' => $category->id,
                'user_id'     => $user->id,
                'subscribed'  => 0
            ]);
        }

        $posts = Post::get();

        foreach ($posts as $post) {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
        }

        Profile::create([
            'user_id'      => $user->id,
            'bio'          => null,
            'website_link' => null,
            'image'        => null
        ]);

        Address::create([
            'user_id'     => $user->id,
            'streetname'  => null,
            'housenumber' => null,
            'zipcode'     => null,
            'city'        => null,
            'state'       => null,
        ]);

        UserSetting::create([
            'user_id' => $user->id,
        ]);
    }
    public function deleted(User $user)
    {
        $categories = Category::get()->pluck('id');

        Sub::query()
            ->whereIn('category_id', $categories)
            ->where('user_id', $user->id)
            ->delete();

        Like::query()
            ->where('user_id', $user->id)
            ->delete();
    }
}
