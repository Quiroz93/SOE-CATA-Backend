<?php

use App\Http\Controllers\Admin\ProgramaController;
use App\Http\Controllers\Admin\WelcomeController;
use App\Http\Controllers\Admin\OfertaController;
use App\Http\Controllers\Admin\NoticiaController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\CompetenciaController;
use App\Http\Controllers\Admin\CentroController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PreinscritoController;
use App\Http\Controllers\Admin\InscritoController;
use App\Http\Controllers\Admin\NovedadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', /* 'role:admin' o 'permission:manage-admin' */])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
        Route::resource('programas', ProgramaController::class);
        Route::resource('ofertas', OfertaController::class);
        Route::resource('noticias', NoticiaController::class);
        Route::resource('instructores', InstructorController::class);
        Route::resource('competencias', CompetenciaController::class);
        Route::resource('centros', CentroController::class);
        Route::resource('users', UserController::class);
        Route::resource('preinscritos', PreinscritoController::class);
        Route::resource('inscritos', InscritoController::class);
        Route::resource('novedades', NovedadController::class);
    });
