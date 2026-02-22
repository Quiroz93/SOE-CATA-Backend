<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Preinscrito;
use App\Models\Programa;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'documento' => 'required|string|max:20',
            'email' => 'required|email',
            'telefono' => 'required|string|max:20',
            'programa_id' => [
                'required',
                'exists:programas,id',
            ],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Errores de validación', 422, $validator->errors());
        }

        $data = $validator->validated();

        // Validar duplicado documento + programa
        $exists = Preinscrito::where('documento', $data['documento'])
            ->where('programa_id', $data['programa_id'])
            ->exists();
        if ($exists) {
            return $this->errorResponse('Ya existe una preinscripción para este documento y programa', 409);
        }

        // Validar que el programa esté activo
        $programa = Programa::where('id', $data['programa_id'])
            ->where('estado', 'publicado')
            ->first();
        if (!$programa) {
            return $this->errorResponse('El programa no está disponible para preinscripción', 400);
        }

        // Validar cupos disponibles si el modelo lo tiene
        if (isset($programa->cupos)) {
            $inscritos = Preinscrito::where('programa_id', $programa->id)->count();
            if ($inscritos >= $programa->cupos) {
                return $this->errorResponse('No hay cupos disponibles para este programa', 409);
            }
        }

        $preinscripcion = Preinscrito::create($data);

        return $this->successResponse(new PreinscripcionResource($preinscripcion), 'Preinscripción registrada correctamente', 201);
    }
}
