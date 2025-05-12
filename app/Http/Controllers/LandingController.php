<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(9)
            ->get();

        return view('landing', compact('restaurants'));
    }
}
