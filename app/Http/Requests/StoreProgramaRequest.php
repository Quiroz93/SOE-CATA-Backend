<?php

namespace App\Http\Requests;

use App\Domain\Programa\Enums\EstadoPrograma;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'estado' => ['nullable', new Enum(EstadoPrograma::class)],
            // ...otros campos...
            'redes_ids' => 'required|array',
            'redes_ids.*' => 'exists:red_formacion,id',
        ];
    }
}
