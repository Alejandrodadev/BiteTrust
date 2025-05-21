<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Comprobar el usuario conectado
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'restaurant_id' => ['required','exists:restaurants,id'],
            'rating'        => ['required','integer','between:1,5'],
            'comment'       => ['required','string','max:600'],
            'photos'        => ['nullable','array','max:5'],
            'photos.*'      => ['image','max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.max' => 'Tu reseña no puede exceder los :max caracteres.',
            'photos.max'  => 'Solo puedes subir un máximo de :max fotos.',
            // …
        ];
    }
}
