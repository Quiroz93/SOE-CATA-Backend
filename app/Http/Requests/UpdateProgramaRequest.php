<?php

namespace App\Http\Requests;

use App\Domain\Programa\Enums\EstadoPrograma;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateProgramaRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('programa.update');
    }

    public function rules()
    {
        $programa = $this->route('programa');
        $programaId = is_object($programa) ? $programa->id : $programa;

        return [
            'nombre' => 'required|string|max:255',
            'ficha' => ['required', 'string', 'max:50', Rule::unique('programas', 'ficha')->ignore($programaId)],
            'estado' => ['nullable', new Enum(EstadoPrograma::class)],
            // ...otros campos...
            'redes_ids' => 'required|array',
            'redes_ids.*' => 'exists:red_formacion,id',
        ];
    }
}
