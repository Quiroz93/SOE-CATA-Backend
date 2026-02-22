<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Return a successful JSON response.
     *
     * @param mixed $data
     * @param mixed $meta
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, $meta = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'errors' => [],
            'meta' => $meta,
        ], $status);
    }

    /**
     * Return an error JSON response.
     *
     * @param array $errors
     * @param int $status
     * @param mixed $meta
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error(array $errors = [], int $status = 400, $meta = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => null,
            'errors' => $errors,
            'meta' => $meta,
        ], $status);
    }
}
