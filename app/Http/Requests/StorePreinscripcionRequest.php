<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\OfertaPrograma;

class StorePreinscripcionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'tipo_documento' => 'required|in:CC,TI,CE,PAS,PPT',
            'documento' => 'required|string|max:255',
            'oferta_programa_id' => [
                'required',
                'integer',
                'exists:oferta_programa,id',
                function ($attribute, $value, $fail) {
                    $registro = OfertaPrograma::activo()->find($value);
                    if (!$registro) {
                        $fail('La oferta seleccionada no está activa.');
                    }
                    // Validar cupos disponibles si aplica
                    if ($registro && $registro->cupos <= 0) {
                        $fail('No hay cupos disponibles para esta oferta.');
                    }
                }
            ],
        ];
    }
}
