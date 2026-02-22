<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Trait ApiResponse
 * Estandariza las respuestas JSON para endpoints API públicos y privados.
 */
trait ApiResponse
{
    /**
     * Retorna una respuesta exitosa estándar.
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @param array $meta
     * @return JsonResponse
     */
    public function successResponse(
        $data = null,
        string $message = 'Operación exitosa',
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $status);
    }

    /**
     * Retorna una respuesta de error estándar.
     *
     * @param string $message
     * @param int $status
     * @param mixed $errors
     * @return JsonResponse
     */
    public function errorResponse(
        string $message = 'Error en la operación',
        int $status = 400,
        $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'meta' => [
                'errors' => $errors,
            ],
        ], $status);
    }

    /**
     * Retorna una respuesta paginada estándar usando Resource Collection.
     *
     * @param LengthAwarePaginator $paginator
     * @param string $message
     * @param string|null $resourceClass
     * @return JsonResponse
     */
    public function paginatedResponse(
        LengthAwarePaginator $paginator,
        string $message = 'Operación exitosa',
        ?string $resourceClass = null
    ): JsonResponse {
        $data = $resourceClass
            ? $resourceClass::collection($paginator)->items()
            : $paginator->items();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ], 200);
    }

    /**
     * Retorna una respuesta de no autenticado estándar.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function unauthorizedResponse(string $message = 'No autenticado'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'meta' => [
                'errors' => ['auth' => $message],
            ],
        ], 401);
    }
}
