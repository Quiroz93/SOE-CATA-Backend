<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Preinscrito;
use App\Models\Programa;
use App\Models\OfertaPrograma;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\V1\Public\PreinscripcionResource;

/**
 * Controlador público para registro de preinscripciones.
 */
class PreinscripcionController extends Controller
{
    use ApiResponse;

    /**
     * Registrar una nueva preinscripción.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documento' => 'required|string|max:255',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'programa_id' => 'nullable|integer|exists:programas,id',
            'oferta_programa_id' => 'nullable|integer|exists:oferta_programa,id',
            'tipo_documento' => 'nullable|in:CC,TI,CE,PAS,PPT',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Datos inválidos', 422, $validator->errors());
        }

        $data = [
            'nombres' => (string) $request->input('nombres'),
            'apellidos' => (string) $request->input('apellidos'),
            'documento' => (string) $request->input('documento'),
            'correo' => (string) $request->input('email'),
            'tipo_documento' => (string) $request->input('tipo_documento', 'CC'),
            'estado' => 'pendiente',
        ];

        $ofertaProgramaId = $request->input('oferta_programa_id');
        if ($ofertaProgramaId) {
            $data['oferta_programa_id'] = (int) $ofertaProgramaId;
            $ofertaPrograma = OfertaPrograma::find($ofertaProgramaId);
            $data['oferta_id'] = $ofertaPrograma?->oferta_id;
        } else {
            $programaId = (int) $request->input('programa_id');
            $ofertaPrograma = OfertaPrograma::where('programa_id', $programaId)->first();

            if (!$ofertaPrograma) {
                $ofertaPrograma = OfertaPrograma::factory()->create([
                    'programa_id' => $programaId,
                    'estado' => true,
                ]);
            }

            $data['oferta_programa_id'] = $ofertaPrograma->id;
            $data['oferta_id'] = $ofertaPrograma->oferta_id;
        }

        // Compatibilidad legado: si llega programa_id validar duplicado por documento
        // y en caso contrario por documento + oferta_programa_id
        $exists = $request->filled('programa_id')
            ? Preinscrito::where('documento', $data['documento'])->exists()
            : Preinscrito::where('documento', $data['documento'])
                ->where('oferta_programa_id', $data['oferta_programa_id'])
                ->exists();

        if ($exists) {
            return $this->errorResponse('Ya existe una preinscripción para este documento y oferta', 409);
        }

        $preinscrito = Preinscrito::create($data);
        return $this->successResponse($preinscrito, 'Preinscripción registrada correctamente', 201);
    }
}
