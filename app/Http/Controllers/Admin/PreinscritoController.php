<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preinscrito;
use Illuminate\Http\Request;

class PreinscritoController extends Controller
{
    public function index() { return view('admin.preinscritos.index'); }
    public function show(Preinscrito $preinscrito) { return view('admin.preinscritos.show'); }
    public function create() { return view('admin.preinscritos.create'); }
    public function store(Request $request) { return view('admin.preinscritos.index'); }
    public function edit(Preinscrito $preinscrito) { return view('admin.preinscritos.edit'); }
    public function update(Request $request, Preinscrito $preinscrito) { return view('admin.preinscritos.index'); }
    public function destroy(Preinscrito $preinscrito) { return view('admin.preinscritos.index'); }
}
