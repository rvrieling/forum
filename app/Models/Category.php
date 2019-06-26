<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subs()
    {
        return $this->hasMany(Sub::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
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
