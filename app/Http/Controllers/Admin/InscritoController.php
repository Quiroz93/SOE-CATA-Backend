<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscrito;
use Illuminate\Http\Request;

class InscritoController extends Controller
{
    public function index() { return view('admin.inscritos.index'); }
    public function show(Inscrito $inscrito) { return view('admin.inscritos.show'); }
    public function create() { return view('admin.inscritos.create'); }
    public function store(Request $request) { return view('admin.inscritos.index'); }
    public function edit(Inscrito $inscrito) { return view('admin.inscritos.edit'); }
    public function update(Request $request, Inscrito $inscrito) { return view('admin.inscritos.index'); }
    public function destroy(Inscrito $inscrito) { return view('admin.inscritos.index'); }
}
