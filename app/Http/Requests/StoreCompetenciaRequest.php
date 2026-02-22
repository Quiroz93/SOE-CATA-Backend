<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompetenciaRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('programas.create'); // Ajustar si el permiso es diferente
    }

    public function rules()
    {
        return [
            // TODO: Agregar reglas reales según los campos de Competencia
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:competencias,codigo',
            // ...otros campos...
        ];
    }
}
