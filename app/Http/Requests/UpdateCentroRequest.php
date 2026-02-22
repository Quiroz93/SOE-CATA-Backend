<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCentroRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('centro.update'); // Ajustar si el permiso es diferente
    }

    public function rules()
    {
        return [
            // TODO: Agregar reglas reales según los campos de Centro
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:centros,codigo,' . $this->route('centro'),
            // ...otros campos...
        ];
    }
}
