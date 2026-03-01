<?php

use App\Http\Controllers\Admin\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Metadata\Group;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CentroController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', [WelcomeController::class, 'index']);

Route::get('/dashboard', function () {
	// Redireccionar a admins al dashboard administrativo
	if (Auth::check() && (Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('Administrador'))) {
		return redirect()->route('admin.dashboard');
	}
	return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:admin-panel'])
    ->group(function () {
        Route::resource('centros', CentroController::class);
        Route::resource('usuarios', UserController::class);
    });

require __DIR__.'/api_v1_public.php';
require __DIR__.'/admin.php';
require __DIR__.'/auth.php';

