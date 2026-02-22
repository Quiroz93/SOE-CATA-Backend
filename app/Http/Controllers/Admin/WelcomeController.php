<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WelcomeController extends Controller
{
    /**
     * Muestra la vista de bienvenida del panel admin.
     */
    public function index()
    {
        // Si usas Inertia o Vue SPA, retorna la vista adecuada
        // return Inertia::render('AdminWelcome');
        // O si usas blade:
        // return view('admin.welcome');
        return view('welcome');
    }
}
