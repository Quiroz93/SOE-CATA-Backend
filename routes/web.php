<?php

use App\Http\Controllers\Admin\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Models\User;

Route::get('/', [WelcomeController::class, 'index']);

Route::get('/dashboard', function () {
	// Redireccionar a admins al dashboard administrativo
	/** @var User $user */
	$user = Auth::user();
	
	if ($user && $user->hasAnyRole(['Super Admin', 'Administración del Sistema', 'SuperAdmin', 'Administrador'])) {
		return redirect()->route('admin.dashboard');
	}
	return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/api_v1_public.php';
require __DIR__.'/admin.php';
require __DIR__.'/auth.php';

