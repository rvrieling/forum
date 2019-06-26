<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImagePost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'post_id',
        'image',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
