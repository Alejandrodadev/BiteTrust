<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        // Filtro de ciudades
        $cities = Restaurant::select('city')->distinct()->pluck('city');

        // Coordenadas de filtrado (si vienen en la URL)
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        // Query base
        $query = Restaurant::withAvg('reviews', 'rating')
            ->withCount('reviews');

        // Filtro por nombre
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        // Filtro por ciudad
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Si tenemos lat/lng, añadimos cálculo de distancia y ordenamos por cercanis
        if ($lat && $lng) {
            $lat = (float) $lat;
            $lng = (float) $lng;

            $haversine = "(6371 * acos(
            cos(radians($lat)) *
            cos(radians(latitude)) *
            cos(radians(longitude) - radians($lng)) +
            sin(radians($lat)) *
            sin(radians(latitude))
        ))";

            // Campo distance al SELECT y ordenamos
            $query->addSelect(\DB::raw("$haversine AS distance"))
                ->orderBy('distance', 'asc');
        }

        // Orden según el parámetro 'sort'
        $sort = $request->get('sort', 'popularidad');

        if ($sort === 'recientes') {
            $query->orderByDesc('created_at');
        } elseif ($sort === 'tendencias') {
            $query->withCount([
                'reviews as recent_reviews_count' => function ($q) {
                    $q->where('created_at', '>=', now()->subDays(7));
                }
            ])
                ->orderByDesc('recent_reviews_count')
                ->orderByDesc('reviews_count');
        } else {
            // popularidad se muestra por defecto
            $query->orderByDesc('reviews_count')
                ->orderByDesc('reviews_avg_rating');
        }

        // Paginacion manteniendo los parámetros en la URL
        $restaurants = $query->paginate(12)->withQueryString();

        // lat/lng a la vista para mostrar distancia
        return view('landing', compact('restaurants', 'cities', 'sort', 'lat', 'lng'));
    }

}
