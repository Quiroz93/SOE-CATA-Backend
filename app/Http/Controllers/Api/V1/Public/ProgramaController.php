<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Api\V1\Public\ProgramaResource;

/**
 * Controlador público para consulta de programas.
 */
class ProgramaController extends Controller
{
    use ApiResponse;

    /**
     * Listar programas activos con filtros y paginación.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Programa::query()->where('estado', 'publicado');

        if ($municipio = $request->input('municipio')) {
            $query->where('municipio', $municipio);
        }
        if ($nivel = $request->input('nivel')) {
            $query->where('nivel', $nivel);
        }
        if ($search = $request->input('search')) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('descripcion', 'like', "%$search%")
                  ->orWhere('ficha', 'like', "%$search%")
                  ;
            });
        }

        $programas = $query->orderByDesc('id')->paginate(10);

        return $this->paginatedResponse($programas, 'Listado de programas', ProgramaResource::class);
    }

    /**
     * Mostrar detalle de un programa activo.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $programa = Programa::where('estado', 'publicado')->find($id);

        if (!$programa) {
            return $this->errorResponse('Programa no encontrado', 404);
        }
        return $this->successResponse(new ProgramaResource($programa), 'Programa encontrado');
    }
}
