<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        // Select único de ciudades
        $cities = Restaurant::select('city')->distinct()->pluck('city');

        // Query base con avg y count
        $query = Restaurant::withAvg('reviews', 'rating')
            ->withCount('reviews');

        // Filtro por búsqueda de nombre
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%'. $request->search .'%');
        }

        // Filtro por ciudad
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Ordenamiento
        $sort = $request->get('sort', 'popularidad');

        if ($sort === 'recientes') {
            $query->orderByDesc('created_at');
        }
        elseif ($sort === 'tendencias') {
            $query->withCount(['reviews as recent_reviews_count' => function($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            }])
                ->orderByDesc('recent_reviews_count')
                ->orderByDesc('reviews_count');
        }
        else {
            $query->orderByDesc('reviews_count')
                ->orderByDesc('reviews_avg_rating');
        }

        // Paginamos 9 por página (manteniendo querystring)
        $restaurants = $query->paginate(9)->withQueryString();

        return view('landing', compact('restaurants', 'cities', 'sort'));
    }
}
