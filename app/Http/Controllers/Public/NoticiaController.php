<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Noticia;

class NoticiaController extends Controller
{
    public function index()
    {
        return view('public.noticias.index');
    }
    public function show(Noticia $noticia)
    {
        return view('public.noticias.show');
    }
}
