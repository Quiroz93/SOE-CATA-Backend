<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Centro;

class CentroController extends Controller
{
    public function index()
    {
        $centros = Centro::published()->latest()->paginate(10);
        return view('public.centros.index', compact('centros'));
    }
    public function show(Centro $centro)
    {
        return view('public.centros.show', compact('centro'));
    }
}
