<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use App\Models\Like;
use App\Models\Post;

class LikeController extends Controller
{
    public function update(LikeRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();

        Like::query()
            ->where('user_id', $user->id)
            ->where('post_id', $validated['post_id'])
            ->update([
                'liked' => $validated['liked']
            ]);

        $post = Post::query()
            ->where('id', $validated['post_id'])
            ->select('likes_count')
            ->first();

        Post::query()
            ->where('id', $validated['post_id'])
            ->update([
                'likes_count' => $validated['liked'] == 1 ? $post->likes_count + 1 : $post->likes_count - 1
            ]);

        return response()->json(201);
    }
}
