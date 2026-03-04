<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompetenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'area' => 'nullable|string|max:255',
            'estado' => 'required|in:publicado,borrador',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la competencia es obligatorio',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser publicado o borrador',
        ];
    }
}
