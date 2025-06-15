<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        // Filtro de ciudades
        $cities = Restaurant::select('city')->distinct()->pluck('city');

        // Parámetros de coordenadas
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        // Query base de restaurantes
        $query = Restaurant::withAvg('reviews', 'rating')
            ->withCount('reviews');

        // Filtro por nombre
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Filtro por ciudad
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Distancia Haversine si tenemos lat/lng
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

            $query->addSelect(DB::raw("$haversine AS distance"))
                ->orderBy('distance', 'asc');
        }

        // Orden según 'sort'
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
            // popularidad por defecto
            $query->orderByDesc('reviews_count')
                ->orderByDesc('reviews_avg_rating');
        }

        // Paginación
        $restaurants = $query->paginate(12)->withQueryString();

        // Devolver as la vista
        return view('landing', [
            'restaurants' => $restaurants,
            'cities'      => $cities,
            'sort'        => $sort,
            'lat'         => $lat,
            'lng'         => $lng,
        ]);
    }
}
