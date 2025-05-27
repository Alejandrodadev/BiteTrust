<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewPhoto;
use Illuminate\Support\Facades\Storage;

class ReviewPhotoController extends Controller
{
    /**
     * Elimina solo la foto, sin tocar la reseña.
     */
    public function destroy(Review $review, ReviewPhoto $photo)
    {
        // Asegura que la foto pertenece a esa reseña y al usuario:
        abort_if($photo->review_id !== $review->id, 404);
        abort_if($review->user_id !== auth()->id(), 403);

        // Borrar archivos (optimizada + miniatura)
        $path = str_replace('storage/', '', $photo->photo_url);
        $thumbPath = str_replace('storage/', '', $photo->thumbnail_url);

        Storage::disk('public')->delete([$path, $thumbPath]);

        // Borrar registro
        $photo->delete();

        return back()->with('success', 'Foto eliminada correctamente.');
    }
}
