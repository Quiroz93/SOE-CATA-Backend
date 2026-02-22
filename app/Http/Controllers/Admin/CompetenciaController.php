<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competencia;
use App\Http\Requests\StoreCompetenciaRequest;
use App\Http\Requests\UpdateCompetenciaRequest;

class CompetenciaController extends Controller
{
    public function index()
    {
        $competencias = Competencia::with('programas')->latest()->paginate(15);
        return view('admin.competencias.index', compact('competencias'));
    }

    public function show(Competencia $competencia)
    {
        $competencia->load('programas');
        return view('admin.competencias.show', compact('competencia'));
    }
    public function create()
    {
        return view('admin.competencias.create');
    }
    public function store(StoreCompetenciaRequest $request)
    {
        $data = $request->validated();
        $programas = $request->input('programas', []);
        $competencia = Competencia::create($data);
        $competencia->programas()->sync($programas);
        return redirect()->route('admin.competencias.index')
            ->with('success', 'Competencia creada correctamente.');
    }
    public function edit(Competencia $competencia)
    {
        return view('admin.competencias.edit', compact('competencia'));
    }
    public function update(UpdateCompetenciaRequest $request, Competencia $competencia)
    {
        $data = $request->validated();
        $programas = $request->input('programas', []);
        $competencia->update($data);
        $competencia->programas()->sync($programas);
        return redirect()->route('admin.competencias.index')
            ->with('success', 'Competencia actualizada correctamente.');
    }
    public function destroy(Competencia $competencia)
    {
        $competencia->delete();
        return redirect()->route('admin.competencias.index')
            ->with('success', 'Competencia eliminada correctamente.');
    }
}
