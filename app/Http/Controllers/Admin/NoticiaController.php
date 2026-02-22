<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    public function index() { return view('admin.noticias.index'); }
    public function show(Noticia $noticia) { return view('admin.noticias.show'); }
    public function create() { return view('admin.noticias.create'); }
    public function store(Request $request) { return view('admin.noticias.index'); }
    public function edit(Noticia $noticia) { return view('admin.noticias.edit'); }
    public function update(Request $request, Noticia $noticia) { return view('admin.noticias.index'); }
    public function destroy(Noticia $noticia) { return view('admin.noticias.index'); }
}
