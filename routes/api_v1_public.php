<?php

use App\Http\Controllers\Api\V1\Public\OfertaPublicaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Public\ProgramaController;
use App\Http\Controllers\Api\V1\Public\PreinscripcionController;
use App\Http\Controllers\Publico\ProgramaController as ProgramaPublicoController;

Route::prefix('v1/public')
    ->middleware(['api'])
    ->group(function () {
        Route::get('ofertas', [OfertaPublicaController::class, 'index'])->name('api.v1.ofertas.index');
        Route::get('ofertas/{slug}', [OfertaPublicaController::class, 'show'])->name('api.v1.ofertas.show');
        Route::get('ofertas/{slug}/programas', [OfertaPublicaController::class, 'programas'])->name('api.v1.ofertas.programas');
        Route::get('programas', [ProgramaController::class, 'index'])->name('api.v1.programas.index');
        Route::get('programas/{id}', [ProgramaController::class, 'show'])->name('api.v1.programas.show');
        Route::post('preinscripciones', [PreinscripcionController::class, 'store'])->middleware('throttle:10,1')->name('api.v1.preinscripciones.store');
    });

Route::prefix('publico')
    ->middleware(['api'])
    ->group(function () {
        Route::get('programas', [ProgramaPublicoController::class, 'index'])->name('api.publico.programas.index');
        Route::get('programas/{slug}', [ProgramaPublicoController::class, 'show'])->name('api.publico.programas.show');
    });
