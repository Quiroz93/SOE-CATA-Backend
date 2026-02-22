<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramaRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('programa.create');
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'ficha' => 'required|string|max:50|unique:programas,ficha',
            // ...otros campos...
            'redes_ids' => 'required|array',
            'redes_ids.*' => 'exists:red_formacion,id',
        ];
    }
}
