<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSummary extends Model
{
    use HasFactory;

    protected $fillable = ['restaurant_id', 'data'];

    protected $casts = [
        'data' => 'array',
    ];
}
