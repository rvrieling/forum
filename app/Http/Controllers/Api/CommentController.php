<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        return Comment::query()
            ->post($request->input('post'))
            ->user($request->input('user'))
            ->get();
    }

    public function create(CommentRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();

        $post_id    = $validated['post_id'];
        $content    = $validated['content'];
        $is_comment = isset($validated['is_comment']) !== false ? $validated['is_comment'] : 0;
        $comment_id = isset($validated['comment_id']) !== false ? $validated['comment_id'] : null;

        Comment::query()
            ->create([
                'post_id'       => $post_id,
                'user_id'       => $user->id,
                'content'       => $content,
                'is_comment'    => $is_comment,
                'comment_id'    => $comment_id,
            ]);

        return response()->json(201);
    }

    public function edit(CommentRequest $request)
    {
        $user = user($request);

        $validated = $request->validated();

        $id      = $validated['id'];
        $post_id = $validated['post_id'];
        
        $comment = Comment::query()
            ->where('id', $id)
            ->where('post_id', $post_id)
            ->first();

        $content = empty($validated['content']) !== false ? $comment->content : $validated['content'];

        if ($user->role === 'admin' | $comment->user_id === $user->id) {
            $comment->update([
                'content' => $content
            ]);

            return response()->json(201);
        }

        return response()->json(401);
    }

    public function delete(Request $request)
    {
        $user = user($request);

        $id = $request->input('id');

        $comment = Comment::query()
            ->where('id', $id);

        if ($user->role === 'admin' | $comment->first()->user_id === $user->id) {
            $comment->delete();

            return response()->json(410);
        }

        return response()->json(401);
    }
}
