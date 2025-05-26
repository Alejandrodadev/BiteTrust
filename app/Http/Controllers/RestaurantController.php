<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Http;

class RestaurantController extends Controller
{
    public function show(Restaurant $restaurant)
    {
        // Rating de reseñas
        $restaurant->loadCount('reviews')
            ->loadAvg('reviews', 'rating');

        // Reseñas propias
        $reviews = $restaurant
            ->reviews()
            ->with(['photos', 'user'])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Reseñas externas
        $externalReviews = $restaurant
            ->externalReviews()
            ->orderByDesc('rating')
            ->get();


        // Fotos del lugar (Google Places)
        $apiKey = config('services.google_places.key');
        $resp = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id' => $restaurant->google_place_id,
            'key'      => $apiKey,
            'fields'   => 'photos',
        ]);

        // Si Google devuelve fotos, construir URLs
        $placePhotos = collect($resp->json('result.photos', []))
            ->map(function($photo) use ($apiKey) {
                return "https://maps.googleapis.com/maps/api/place/photo"
                    . "?maxwidth=800"
                    . "&photoreference={$photo['photo_reference']}"
                    . "&key={$apiKey}";
            })
            ->all();

        return view('restaurants.show', compact(
            'restaurant',
            'reviews',
            'externalReviews',
            'placePhotos'
        ));
    }
}
