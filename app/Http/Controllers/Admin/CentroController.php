<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Http\Requests\StoreCentroRequest;
use App\Http\Requests\UpdateCentroRequest;

class CentroController extends Controller
{
    public function index()
    {
        $centros = Centro::latest()->paginate(15);
        return view('admin.centros.index', compact('centros'));
    }
    public function show(Centro $centro)
    {
        return view('admin.centros.show', compact('centro'));
    }
    public function create()
    {
        return view('admin.centros.create');
    }
    public function store(StoreCentroRequest $request)
    {
        $centro = Centro::create($request->validated());
        return redirect()->route('admin.centros.index')
            ->with('success', 'Centro creado correctamente.');
    }
    public function edit(Centro $centro)
    {
        return view('admin.centros.edit', compact('centro'));
    }
    public function update(UpdateCentroRequest $request, Centro $centro)
    {
        $centro->update($request->validated());
        return redirect()->route('admin.centros.index')
            ->with('success', 'Centro actualizado correctamente.');
    }
    public function destroy(Centro $centro)
    {
        $centro->delete();
        return redirect()->route('admin.centros.index')
            ->with('success', 'Centro eliminado correctamente.');
    }
}
