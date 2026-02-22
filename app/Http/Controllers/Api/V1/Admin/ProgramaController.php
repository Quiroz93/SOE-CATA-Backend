<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Http\Requests\ProgramaStoreRequest;
use App\Support\ApiResponse;
use App\Http\Resources\ProgramaResource;
use Illuminate\Support\Facades\Log;

class ProgramaController extends Controller
{
    public function index()
    {
        $paginator = Programa::latest()->paginate();
        $data = ProgramaResource::collection($paginator->items());
        $meta = [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
        ];
        return ApiResponse::success($data, $meta);
    }

    public function show($id)
    {
        $programa = Programa::find($id);
        if (!$programa) {
            return ApiResponse::error(['Programa no encontrado'], 404);
        }
        return ApiResponse::success(new ProgramaResource($programa));
    }

    public function store(ProgramaStoreRequest $request)
    {
        try {
            Log::info('Datos validados recibidos:', $request->validated());
            $programa = Programa::create($request->validated());
            return ApiResponse::success(new ProgramaResource($programa), null, 201);
        } catch (\Throwable $e) {
            Log::error('Error al crear programa:', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->validated()
            ]);
            return response()->json(['message' => 'Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    // Métodos update y destroy eliminados según requerimiento de solo lectura y creación para la API pública.
}
