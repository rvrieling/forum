<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImagePost;
use App\Models\User;

class ImageController extends Controller
{
    public function postImage($post_id)
    {
        $image_post = ImagePost::query()
            ->where('post_id', $post_id)
            ->first();
            
        return response()->file('./storage/images/posts/' . $image_post->image . '.png');
    }

    public function userImage($user_id)
    {
        $user = User::query()
            ->with('profile')
            ->where('id', $user_id)
            ->first();

        return response()->file('./storage/images/users/' . $user->profile->image . '.jpg');
    }

    public function chatImage($name)
    {
        return response()->file('./storage/images/chats/' . $name . '.jpg');
    }

    public function ProfileImage($username)
    {
        $profile = User::query()
            ->where('user_name', $username)
            ->with('profile.profileImage')
            ->first();
        
        return response()->file('./storage/images/profile_images/' . $profile->profile->profileImage->image . '.png');
    }
}
