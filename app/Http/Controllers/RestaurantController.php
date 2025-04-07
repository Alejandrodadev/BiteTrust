<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function show(Restaurant $restaurant)
    {
        $restaurant->load(['reviews.user', 'reviews.photos', 'reviews.votes']);
        return view('restaurants.show', compact('restaurant'));
    }
}
