<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::all();
        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('reviews.create');
    }

    public function store(Request $request)
    {

    }

    public function show($review)
    {
        $review = Review::find($review);

        return view('reviews.show', compact('review'));


    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        // Validar los datos del formulario
        $request->validate([
            'comment' => 'required|string|max:255',
            'rating' => 'required|integer|between:1,5',
        ]);

        $review->update([
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return redirect()->route('reviews.show', $review->id)
            ->with('success', 'Reseña actualizada correctamente.');
    }


    public function destroy(Review $review)
    {

    }
}
