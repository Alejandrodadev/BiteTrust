<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para actualizar esta reseña.
     */
    public function authorize(): bool
    {
        $review = $this->route('review');
        return auth()->check() && $review && $review->user_id === auth()->id();
    }

    /**
     * Reglas de validación para la actualización de reseñas.
     */
    public function rules(): array
    {
        return [
            'rating'   => ['required', 'integer', 'between:1,5'],
            'comment'  => ['required', 'string',  'max:600'],
            'photos'   => ['nullable', 'array',   'max:5'],
            'photos.*' => ['image',    'max:2048'],
        ];
    }

    /**
     * Mensajes personalizados de error.
     */
    public function messages(): array
    {
        return [
            'rating.required'    => 'La calificación es obligatoria.',
            'rating.between'     => 'La calificación debe estar entre 1 y 5.',
            'comment.required'   => 'El comentario no puede quedar vacío.',
            'comment.max'        => 'El comentario no puede exceder los :max caracteres.',
            'photos.max'         => 'Solo puedes subir hasta :max fotos.',
            'photos.*.image'     => 'Cada archivo debe ser una imagen válida.',
            'photos.*.max'       => 'Cada imagen no puede superar los :max kilobytes.',
        ];
    }
}
