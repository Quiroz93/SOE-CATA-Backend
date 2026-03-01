<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CentroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $centroId = $this->route('centro') ? $this->route('centro')->id : null;
        
        return [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:centros,codigo,' . $centroId,
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'estado' => 'boolean',
        ];
    }
}
