<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'streetname',
        'housenumber',
        'zipcode',
        'city',
        'state',
        'country'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUser($q, $name)
    {
        return $q->whereHas('user', function ($query) use ($name) {
            $query->where('user_name', $name);
        });
    }
}
