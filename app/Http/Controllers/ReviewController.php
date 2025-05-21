<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Models\ReviewPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    /**
     * Mostrar todas las reseñas (sin paginar).
     */
    public function index()
    {
        $reviews = Review::with(['user', 'restaurant', 'photos'])->get();
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Mostrar formulario para crear una reseña (raro si lo haces desde restaurante).
     */
    public function create()
    {
        return view('reviews.create');
    }

    /**
     * Almacenar una nueva reseña.
     */
    public function store(StoreReviewRequest $request)
    {
        // Verificar duplicado
        if (Review::where('user_id', Auth::id())
            ->where('restaurant_id', $request->restaurant_id)
            ->exists()
        ) {
            return back()->with('error', 'Ya dejaste una reseña para este restaurante.');
        }

        $review = Review::create($request->only([
                'restaurant_id', 'rating', 'comment'
            ]) + ['user_id' => Auth::id()]);

        $this->processPhotos($review, $request->file('photos', []));

        return redirect()
            ->route('restaurants.show', $review->restaurant_id)
            ->with('success', '¡Reseña publicada con éxito!');
    }

    /**
     * Mostrar una reseña concreta.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'photos']);
        return view('reviews.show', compact('review'));
    }

    /**
     * Listar reseñas del usuario autenticado.
     */
    public function userReviews()
    {
        $reviews = Review::with(['restaurant', 'photos'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('reviews.user', compact('reviews'));
    }

    /**
     * Mostrar formulario de edición (solo autor).
     */
    public function edit(Review $review)
    {
        abort_if($review->user_id !== Auth::id(), 403);
        return view('reviews.edit', compact('review'));
    }

    /**
     * Actualizar reseña existente.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        // Comprueba autoría
        abort_if($review->user_id !== Auth::id(), 403);

        // Control de cantidad máxima de fotos
        $existing = $review->photos()->count();
        $incoming = count($request->file('photos', []));
        if ($existing + $incoming > 5) {
            return back()
                ->withErrors(['photos' => 'Máximo 5 fotos por reseña.'])
                ->withInput();
        }

        $review->update($request->only(['rating', 'comment']));
        $this->processPhotos($review, $request->file('photos', []));

        return back()->with('success', 'Reseña actualizada correctamente.');
    }

    /**
     * Borrar reseña y sus fotos (solo autor).
     */
    public function destroy(Review $review)
    {
        abort_if($review->user_id !== Auth::id(), 403);

        foreach ($review->photos as $photo) {
            $path = str_replace('storage/', '', $photo->photo_url);
            Storage::disk('public')->delete($path);
            $photo->delete();
        }

        $review->delete();

        return redirect()
            ->route('reviews.user')
            ->with('success', 'Reseña eliminada correctamente.');
    }

    /**
     * Procesa imágenes (WebP + thumbnail) y registra en BD.
     */
    protected function processPhotos(Review $review, array $files): void
    {
        foreach ($files as $file) {
            $uuid      = Str::uuid();
            $dir       = "restaurants/{$review->id}";
            $origName  = "{$uuid}.webp";
            $thumbName = "{$uuid}_thumb.webp";

            $publicDir = storage_path("app/public/{$dir}");
            if (! is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }

            $img       = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $origPath  = "{$publicDir}/{$origName}";
            imagewebp($img, $origPath, 90);

            $w     = imagesx($img);
            $h     = imagesy($img);
            $thumb = imagescale($img, 300, intval($h * (300 / $w)));
            $thumbPath = "{$publicDir}/{$thumbName}";
            imagewebp($thumb, $thumbPath, 80);

            imagedestroy($img);
            imagedestroy($thumb);

            ReviewPhoto::create([
                'review_id'     => $review->id,
                'photo_url'     => "storage/{$dir}/{$origName}",
                'thumbnail_url' => "storage/{$dir}/{$thumbName}",
            ]);
        }
    }
}
