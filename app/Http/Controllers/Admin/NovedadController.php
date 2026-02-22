<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Novedad;
use Illuminate\Http\Request;

class NovedadController extends Controller
{
    public function index() { return view('admin.novedades.index'); }
    public function show(Novedad $novedad) { return view('admin.novedades.show'); }
    public function create() { return view('admin.novedades.create'); }
    public function store(Request $request) { return view('admin.novedades.index'); }
    public function edit(Novedad $novedad) { return view('admin.novedades.edit'); }
    public function update(Request $request, Novedad $novedad) { return view('admin.novedades.index'); }
    public function destroy(Novedad $novedad) { return view('admin.novedades.index'); }
}
