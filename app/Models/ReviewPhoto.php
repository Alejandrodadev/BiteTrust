<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReviewPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'photo_url',      // WebP optimizada (tamaño real)
        'thumbnail_url',  // versión pequeña
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
