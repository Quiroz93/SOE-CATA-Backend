<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Http\Requests\StoreProgramaRequest;
use App\Http\Requests\UpdateProgramaRequest;

class ProgramaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Programa::class, 'programa');
    }

    public function index()
    {
        $programas = Programa::latest()->paginate(15);
        return view('admin.programas.index', compact('programas'));
    }
    public function show(Programa $programa)
    {
        return view('admin.programas.show', compact('programa'));
    }
    public function create()
    {
        return view('admin.programas.create');
    }
    public function store(StoreProgramaRequest $request)
    {
        $data = $request->validated();
        $redesIds = $request->input('redes_ids', []);
        $programa = Programa::create($data);
        $programa->redesFormacion()->sync($redesIds);
        return redirect()->route('admin.programas.index')
            ->with('success', 'Programa creado correctamente.');
    }
    public function edit(Programa $programa)
    {
        return view('admin.programas.edit', compact('programa'));
    }
    public function update(UpdateProgramaRequest $request, Programa $programa)
    {
        $data = $request->validated();
        $redesIds = $request->input('redes_ids', []);
        $programa->update($data);
        $programa->redesFormacion()->sync($redesIds);
        return redirect()->route('admin.programas.index')
            ->with('success', 'Programa actualizado correctamente.');
    }
    public function destroy(Programa $programa)
    {
        $programa->delete();
        return redirect()->route('admin.programas.index')
            ->with('success', 'Programa eliminado correctamente.');
    }
}
