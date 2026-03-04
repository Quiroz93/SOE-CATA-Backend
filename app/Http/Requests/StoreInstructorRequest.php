<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstructorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'perfil_descriptivo' => 'required|string',
            'experiencia' => 'nullable|string',
            'activo' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del instructor es obligatorio',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres',
            'perfil_descriptivo.required' => 'El perfil descriptivo es obligatorio',
        ];
    }
}
