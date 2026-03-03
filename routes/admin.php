<?php

use App\Http\Controllers\Admin\ProgramaController;
use App\Http\Controllers\Admin\WelcomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DashboardStatsController;
use App\Http\Controllers\Admin\OfertaController;
use App\Http\Controllers\Admin\NoticiaController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\CompetenciaController;
use App\Http\Controllers\Admin\CentroController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PreinscritoController;
use App\Http\Controllers\Admin\PreinscritorImportExportController;
use App\Http\Controllers\Admin\InscritoController;
use App\Http\Controllers\Admin\NovedadController;
use App\Http\Controllers\Admin\ReporteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', /* 'role:admin' o 'permission:manage-admin' */])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/dashboard/estadisticas/upload', [DashboardStatsController::class, 'upload'])->name('dashboard.stats.upload');
        Route::get('/dashboard/estadisticas/download-excel', [DashboardStatsController::class, 'downloadExcel'])->name('dashboard.stats.downloadExcel');
        Route::get('/dashboard/estadisticas/download-pdf', [DashboardStatsController::class, 'downloadPDF'])->name('dashboard.stats.downloadPDF');
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::resource('programas', ProgramaController::class);
        Route::resource('ofertas', OfertaController::class);
        Route::resource('noticias', NoticiaController::class);
        Route::resource('instructores', InstructorController::class);
        Route::resource('competencias', CompetenciaController::class);
        Route::resource('centros', CentroController::class);
        Route::resource('usuarios', UserController::class);
        
        // Rutas para import/export de preinscritos
        Route::get('preinscritos/template', [PreinscritorImportExportController::class, 'downloadTemplate'])->name('preinscritos.downloadTemplate');
        Route::get('preinscritos/import', [PreinscritorImportExportController::class, 'showImportForm'])->name('preinscritos.showImportForm');
        Route::post('preinscritos/import', [PreinscritorImportExportController::class, 'handleImport'])->name('preinscritos.handleImport');
        
        Route::resource('preinscritos', PreinscritoController::class);
        Route::resource('inscritos', InscritoController::class);
        Route::resource('novedades', NovedadController::class);
    });
