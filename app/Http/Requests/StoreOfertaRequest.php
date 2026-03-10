<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreOfertaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // la autorización ya está en authorizeResource
    }

    public function rules(): array
    {
        return [
            'centro_id' => 'required|exists:centros,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',

            'programas' => 'required|array|min:1',

            'programas.*.id' => 'nullable|exists:oferta_programa,id', // para update
            'programas.*.programa_id' => 'required|exists:programas,id',
            'programas.*.centro_id' => 'required|exists:centros,id',
            'programas.*.cupos' => 'required|integer|min:1',
            'programas.*.modalidad' => 'required|string|max:100',
            'programas.*.jornada' => 'required|in:diurna,nocturna,mixta',
            'programas.*.municipio' => 'required|string|max:255',
            'programas.*.instructor_id' => 'required|exists:instructores,id',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $programas = $this->input('programas', []);
            $combinaciones = [];
            foreach ($programas as $index => $programa) {
                $key = $programa['programa_id'] . '-' . $programa['centro_id'];
                if (in_array($key, $combinaciones)) {
                    $validator->errors()->add(
                        "programas.$index.programa_id",
                        'No se permiten programas duplicados para el mismo centro en la misma oferta.'
                    );
                }
                $combinaciones[] = $key;
            }
        });
    }
}
