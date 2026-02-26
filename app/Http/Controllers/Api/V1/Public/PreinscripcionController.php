<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePreinscripcionRequest;
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
    public function store(StorePreinscripcionRequest $request)
    {
        $data = $request->validated();
        // Validar duplicado documento + oferta_programa
        $exists = Preinscrito::where('documento', $data['documento'])
            ->where('oferta_programa_id', $data['oferta_programa_id'])
            ->exists();
        if ($exists) {
            return $this->errorResponse('Ya existe una preinscripción para este documento y oferta', 409);
        }
        $preinscrito = Preinscrito::create($data);
        return $this->successResponse($preinscrito, 'Preinscripción registrada correctamente', 201);
    }
}
