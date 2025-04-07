<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model {

    protected $fillable = [
        'name',
        'address',
        'city',
        'country',
        'google_place_id',
        'website',
        'schedule'
    ];

    protected $casts = [
        'schedule' => 'array',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
