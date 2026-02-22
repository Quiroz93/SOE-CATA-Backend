<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HistoriaExito;

class HistoriaExitoController extends Controller
{
    public function index()
    {
        return view('public.historias_exito.index');
    }
    public function show(HistoriaExito $historia)
    {
        return view('public.historias_exito.show');
    }
}
