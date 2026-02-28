<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Http\Requests\CentroRequest;
use Illuminate\Http\Request;

class CentroController extends Controller
{
    public function index()
    {
        $centros = Centro::orderBy('nombre')->paginate(15);
        return view('admin.centros.index', compact('centros'));
    }

    public function create()
    {
        return view('admin.centros.create');
    }

    public function store(CentroRequest $request)
    {
        $centro = Centro::create($request->validated());
        return redirect()->route('admin.centros.show', $centro)
            ->with('success', 'Centro creado correctamente');
    }

    public function show(Centro $centro)
    {
        return view('admin.centros.show', compact('centro'));
    }

    public function edit(Centro $centro)
    {
        return view('admin.centros.edit', compact('centro'));
    }

    public function update(CentroRequest $request, Centro $centro)
    {
        $centro->update($request->validated());
        return redirect()->route('admin.centros.show', $centro)
            ->with('success', 'Centro actualizado correctamente');
    }

    public function destroy(Centro $centro)
    {
        $centro->delete();
        return redirect()->route('admin.centros.index')
            ->with('success', 'Centro eliminado correctamente');
    }
}
