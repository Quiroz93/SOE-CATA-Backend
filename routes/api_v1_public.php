<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Public\ProgramaController;
use App\Http\Controllers\Api\V1\Public\PreinscripcionController;

Route::prefix('api/v1/public')
    ->middleware(['api'])
    ->group(function () {
        Route::get('programas', [ProgramaController::class, 'index'])->name('api.v1.public.programas.index');
        Route::get('programas/{programa}', [ProgramaController::class, 'show'])->name('api.v1.public.programas.show');
        Route::post('preinscripciones', [PreinscripcionController::class, 'store'])->name('api.v1.public.preinscripciones.store');
    });
