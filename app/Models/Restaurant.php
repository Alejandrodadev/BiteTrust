<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = [
        'name',
        'address',
        'city',
        'country',
        'google_place_id',
        'website',
        'schedule',
        'latitude',
        'longitude',
        'ratingGoogle',
        'types',
    ];

    /**
     * Conversión de tipos de atributos.
     */
    protected $casts = [
        'schedule' => 'array',
        'types' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'ratingGoogle' => 'float',
    ];

    /**
     * Relación: un restaurante tiene muchas reseñas.
     */
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    /**
     * Relación: todas las fotos asociadas al restaurante,
     * obtenidas a través de las reseñas.
     */
    public function photos()
    {
        return $this->hasManyThrough(
            \App\Models\ReviewPhoto::class,
            \App\Models\Review::class,
            'restaurant_id',   // FK en la tabla reviews
            'review_id',       // FK en la tabla review_photos
            'id',              // PK en la tabla restaurants
            'id'               // PK en la tabla reviews
        );
    }

    /**
     * Scope: ordena por distancia (fórmula de Haversine).
     *
     * @param  float  $lat  Latitud del usuario
     * @param  float  $lng  Longitud del usuario
     */
    public function scopeNearby(Builder $query, float $lat, float $lng): Builder
    {
        // Haversine devuelve la distancia en kilómetros
        return $query->selectRaw('*, (
            6371 * acos(
                cos(radians(?)) *
                cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) *
                sin(radians(latitude))
            )
        ) AS distance', [$lat, $lng, $lat])
            ->orderBy('distance');
    }

    public function externalReviews()
    {
        return $this->hasMany(\App\Models\ExternalReview::class);
    }
}
