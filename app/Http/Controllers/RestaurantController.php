<?php

namespace App\Http\Controllers;

use App\Models\AiSummary;
use App\Models\ExternalReview;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
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
            'key' => $apiKey,
            'fields' => 'photos',
        ]);

        $placePhotos = collect($resp->json('result.photos', []))
            ->map(fn ($photo) => 'https://maps.googleapis.com/maps/api/place/photo'
                .'?maxwidth=800'
                ."&photoreference={$photo['photo_reference']}"
                ."&key={$apiKey}")
            ->all();

        return view('restaurants.show', compact(
            'restaurant',
            'reviews',
            'externalReviews',
            'placePhotos'
        ));
    }

    public function analysis(Request $request, $id)
    {
        $limit = $request->query('limit', 15);

        // Verificar caché
        $cached = AiSummary::where('restaurant_id', $id)->latest()->first();

        if ($cached) {
            $newOwn = Review::where('restaurant_id', $id)
                ->where('created_at', '>', $cached->created_at)
                ->count();

            $newExt = ExternalReview::where('restaurant_id', $id)
                ->where('created_at', '>', $cached->created_at)
                ->count();

            if (($newOwn + $newExt) < 1) {
                return response()->json($cached->data);
            }
        }

        // Obtener reseñas
        $own = Review::where('restaurant_id', $id)
            ->latest()->take($limit)->pluck('comment')->toArray();

        $ext = ExternalReview::where('restaurant_id', $id)
            ->latest()->take($limit)->pluck('text')->toArray();

        $texts = array_slice(array_merge($own, $ext), 0, $limit);

        // Prompt mejorado
        $prompt = 'Analiza estas reseñas separadas por ### y devuelve un objeto JSON con:

1. "analisis_general": resumen no tan corto del restaurante.
2. "platos_mas_mencionados": lista de platos, sin repetir frases entre platos.

Evita usar la misma frase en más de un plato, incluso si aparecen juntos.

Ejemplo:
{
  "analisis_general": "...",
  "platos_mas_mencionados": [
    {
      "plato": "...",
      "menciones": ...,
      "frases_destacadas": ["..."] // distintas por plato
    }
  ]
}
###
'.implode("\n###\n", $texts);

        // Llamada a OpenAI
        $client = \OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo-0125',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un asistente útil.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.2,
        ]);

        $raw = $response->choices[0]->message->content;
        $clean = preg_replace('/```(?:json)?\s*(.*?)\s*```/is', '$1', $raw);

        // Decodificar
        try {
            $decoded = json_decode($clean, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'La IA no devolvió JSON válido',
                'raw' => $raw,
            ], 500);
        }

        // Eliminar frases duplicadas entre platos
        $platos = $decoded['platos_mas_mencionados'] ?? [];
        $frasesUsadas = [];

        foreach ($platos as &$plato) {
            $filtradas = [];
            foreach ($plato['frases_destacadas'] as $frase) {
                if (! in_array($frase, $frasesUsadas)) {
                    $filtradas[] = $frase;
                    $frasesUsadas[] = $frase;
                }
            }
            $plato['frases_destacadas'] = $filtradas;
        }
        unset($plato);

        // Preparar resultado
        $data = [
            'analisis' => $decoded['analisis_general'] ?? '',
            'platos' => $platos,
        ];

        // Guardar en caché
        AiSummary::create([
            'restaurant_id' => $id,
            'data' => $data,
        ]);

        return response()->json($data);
    }
}
