<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Oferta;

class OfertaController extends Controller
{
    public function index()
    {
        return view('public.ofertas.index');
    }
    public function show(Oferta $oferta)
    {
        return view('public.ofertas.show');
    }
}
