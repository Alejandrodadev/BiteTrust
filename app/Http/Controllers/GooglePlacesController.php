<?php

namespace App\Http\Controllers;

use App\Models\ExternalReview;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GooglePlacesController extends Controller
{
    public function register(Request $request)
    {
        $placeId = $request->input('place_id');
        $apiKey = config('services.google_places.key');

        if (! $placeId) {
            return back()->with('error', 'No se recibió suficiente información para registrar.');
        }

        // Verifica si ya está registrado
        if (Restaurant::where('google_place_id', $placeId)->exists()) {
            return back()->with('info', 'Este restaurante ya está registrado.');
        }

        // Consulta detalles a Google
        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id' => $placeId,
            'key' => $apiKey,
            'language' => 'es',
            'fields' => 'name,formatted_address,geometry,rating,place_id,user_ratings_total,website,opening_hours,address_components,types,reviews,price_level',
        ]);

        $detail = $response->json()['result'] ?? null;
        if (! $detail) {
            return back()->with('error', 'No se pudo obtener información del lugar.');
        }

        // Validar tipo permitido (solo restaurantes, bares, cafés, etc.)
        $types = $detail['types'] ?? [];
        $allowedTypes = ['restaurant', 'bar', 'cafe', 'meal_delivery', 'meal_takeaway'];
        if (! collect($types)->intersect($allowedTypes)->count()) {
            return back()->with('error', 'Solo puedes registrar restaurantes, lugares de comida, cafeteria o bares.');
        }

        // Extraer ciudad y país de address_components
        $address = $detail['formatted_address'] ?? '';
        $components = $detail['address_components'] ?? [];

        $city = 'Desconocida';
        $country = 'España';
        foreach ($components as $component) {
            if (in_array('locality', $component['types'])) {
                $city = $component['long_name'];
            }
            if (in_array('country', $component['types'])) {
                $country = $component['long_name'];
            }
        }

        // Crear restaurante y capturar el modelo
        $restaurant = Restaurant::create([
            'name' => $detail['name'],
            'address' => $address,
            'latitude' => $detail['geometry']['location']['lat'],
            'longitude' => $detail['geometry']['location']['lng'],
            'google_place_id' => $detail['place_id'],
            'ratingGoogle' => $detail['rating'] ?? null,
            'city' => $city,
            'country' => $country,
            'schedule' => $detail['opening_hours']['weekday_text'] ?? [],
            'website' => $detail['website'] ?? null,
            'types' => $types,
            'price_level' => $detail['price_level'] ?? null,
        ]);

        // Guardar reseñas de Google (hasta 5)
        if (! empty($detail['reviews'])) {
            foreach ($detail['reviews'] as $gReview) {
                ExternalReview::create([
                    'restaurant_id' => $restaurant->id,
                    'author_name' => $gReview['author_name'],
                    'author_url' => $gReview['author_url'] ?? null,
                    'profile_photo_url' => $gReview['profile_photo_url'] ?? null,
                    'rating' => $gReview['rating'],
                    'text' => $gReview['text'] ?? null,
                    'relative_time_description' => $gReview['relative_time_description'] ?? null,
                    'source' => 'google',
                ]);
            }
        }

        return redirect()
            ->route('landing')
            ->with('success', 'Restaurante registrado, deja tu reseña.');
    }
}
