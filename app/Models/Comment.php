<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'is_comment',
        'comment_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTO(User::class);
    }

    public function scopePost($q, $id)
    {
        if ($id) {
            $q->where('post_id', $id);
        }

        return $q;
    }

    public function scopeUser($q, $name)
    {
        if ($name) {
            $q->whereHas('user', function ($query) use ($name) {
                $query->where('user_name', $name);
            });
        }

        return $q;
    }
}
