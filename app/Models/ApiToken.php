<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ApiToken extends Authenticatable
{
    protected $fillable = [
        'api_token',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
