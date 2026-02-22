<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;
use App\Support\ApiResponse;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            $flatErrors = collect($e->errors())
                ->flatten()
                ->values()
                ->all();
            return ApiResponse::error($flatErrors, 422);
        }
        return parent::render($request, $e);
    }
}
