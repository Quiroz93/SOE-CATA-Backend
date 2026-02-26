<?php

use App\Http\Controllers\Api\V1\Public\OfertaPublicaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Public\ProgramaController;
use App\Http\Controllers\Api\V1\Public\PreinscripcionController;
use App\Http\Controllers\Api\V1\Public\ProgramaPublicoController;

Route::prefix('v1')
    ->middleware(['api'])
    ->group(function () {
        Route::get('ofertas', [OfertaPublicaController::class, 'index'])->name('api.v1.ofertas.index');
        Route::get('ofertas/{slug}', [OfertaPublicaController::class, 'show'])->name('api.v1.ofertas.show');
        Route::get('ofertas/{slug}/programas', [OfertaPublicaController::class, 'programas'])->name('api.v1.ofertas.programas');
        Route::get('programas/{slug}', [ProgramaPublicoController::class, 'show'])->name('api.v1.programas.show');
        Route::post('preinscripciones', [PreinscripcionController::class, 'store'])->middleware('throttle:10,1')->name('api.v1.preinscripciones.store');
    });
