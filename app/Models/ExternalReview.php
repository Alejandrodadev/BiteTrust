<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalReview extends Model
{
    protected $fillable = [
        'restaurant_id',
        'author_name',
        'author_url',
        'profile_photo_url',
        'rating',
        'text',
        'relative_time_description',
        'source',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
