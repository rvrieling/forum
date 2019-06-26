<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $table = 'profile';

    protected $fillable = [
        'user_id',
        'bio',
        'website_link',
        'image'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profileImage()
    {
        return $this->hasOne(ProfileImage::class);
    }

    public function scopeUser($q, $name)
    {
        return $q->whereHas('user', function ($query) use ($name) {
            $query->where('user_name', $name);
        });
    }
}
