<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable =
        ['user_id',
        'restaurant_id',
        'rating',
        'comment'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function photos()
    {
        return $this->hasMany(\App\Models\ReviewPhoto::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(\App\Models\Restaurant::class);
    }



}
