<?php

use App\Http\Controllers\GooglePlacesController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewPhotoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');
Route::get('/access', fn () => view('auth.login-register'))->name('access');

// Cookies y privacidad
Route::view('/cookies-policy', 'cookies.policy')->name('cookies.policy');

/*
|--------------------------------------------------------------------------
| Rutas protegidas por auth
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', fn () => view('dashboard'))->middleware('verified')->name('dashboard');

    // Google Places
    Route::get('/places/test', [GooglePlacesController::class, 'testSearch']);
    Route::post('/restaurants/register', [GooglePlacesController::class, 'register'])->name('restaurants.register');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ruta de análisis de restaurantes con OpenAI
    Route::get('/restaurants/{restaurant}/analysis', [RestaurantController::class, 'analysis'])->name('restaurants.analysis');

    // Borrar foto individual
    Route::delete('/reviews/{review}/photos/{photo}', [ReviewPhotoController::class, 'destroy'])->name('photos.destroy');

    // CRUD de reseñas
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/my-reviews', [ReviewController::class, 'userReviews'])->name('reviews.user');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

require __DIR__.'/auth.php';
