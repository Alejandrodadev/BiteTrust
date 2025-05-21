<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function show(Restaurant $restaurant)
    {
        // Paginamos las reseñas en lugar de get()
        $reviews = $restaurant
            ->reviews()
            ->with(['photos', 'user'])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Enviamos tanto $restaurant como $reviews
        return view('restaurants.show', compact('restaurant', 'reviews'));
    }
}
