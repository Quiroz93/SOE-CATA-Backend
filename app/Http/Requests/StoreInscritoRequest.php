<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInscritoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preinscrito_id' => 'required|exists:preinscritos,id',
            'oferta_id' => 'required|exists:ofertas,id',
            'programa_id' => 'required|exists:programas,id',
            'estado' => 'required|in:inscrito,matriculado,retirado',
        ];
    }

    public function messages(): array
    {
        return [
            'preinscrito_id.required' => 'El preinscrito es obligatorio',
            'preinscrito_id.exists' => 'El preinscrito seleccionado no existe',
            'oferta_id.required' => 'La oferta es obligatoria',
            'oferta_id.exists' => 'La oferta seleccionada no existe',
            'programa_id.required' => 'El programa es obligatorio',
            'programa_id.exists' => 'El programa seleccionado no existe',
            'estado.required' => 'El estado es obligatorio',
        ];
    }
}
