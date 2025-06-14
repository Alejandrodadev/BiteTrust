<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompareRestaurantsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // ya no “required”
            'ids'   => ['nullable', 'array'],
            'ids.*' => ['integer', 'exists:restaurants,id'],
        ];
    }

    public function messages()
    {
        return [
            'ids.array'   => 'Formato inválido.',
            'ids.*.exists'=> 'Restaurante no encontrado.',
        ];
    }
}
