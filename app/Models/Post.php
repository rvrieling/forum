<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'user_id',
        'name',
        'content',
        'likes_count',
        'image_name',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function images()
    {
        return $this->hasOne(ImagePost::class);
    }

    public function scopeCategory($q, $id)
    {
        if ($id) {
            $q->where('category_id', $id);
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
