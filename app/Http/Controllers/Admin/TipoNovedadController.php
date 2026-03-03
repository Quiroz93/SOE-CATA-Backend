<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoNovedad;
use Illuminate\Http\Request;

class TipoNovedadController extends Controller
{
    /**
     * Mostrar lista de tipos de novedades
     */
    public function index(Request $request)
    {
        $query = TipoNovedad::query();

        // Filtro por nombre
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        // Filtro por descripción
        if ($request->filled('descripcion')) {
            $query->where('descripcion', 'like', '%' . $request->descripcion . '%');
        }

        $tiposNovedad = $query->orderBy('nombre')->paginate(15);

        return view('admin.tipo_novedades.index', compact('tiposNovedad'));
    }

    /**
     * Mostrar formulario para crear tipo de novedad
     */
    public function create()
    {
        return view('admin.tipo_novedades.create');
    }

    /**
     * Almacenar tipo de novedad en la base de datos
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:tipo_novedades,nombre',
            'descripcion' => 'nullable|string|max:500',
        ]);

        TipoNovedad::create($validated);

        return redirect()->route('admin.tipo_novedades.index')
            ->with('success', 'Tipo de novedad creado correctamente');
    }

    /**
     * Mostrar formulario para editar tipo de novedad
     */
    public function edit(TipoNovedad $tipoNovedad)
    {
        return view('admin.tipo_novedades.edit', compact('tipoNovedad'));
    }

    /**
     * Actualizar tipo de novedad en la base de datos
     */
    public function update(Request $request, TipoNovedad $tipoNovedad)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:tipo_novedades,nombre,' . $tipoNovedad->id,
            'descripcion' => 'nullable|string|max:500',
        ]);

        $tipoNovedad->update($validated);

        return redirect()->route('admin.tipo_novedades.index')
            ->with('success', 'Tipo de novedad actualizado correctamente');
    }

    /**
     * Eliminar tipo de novedad
     */
    public function destroy(TipoNovedad $tipoNovedad)
    {
        // Validar que no tenga novedades asociadas
        if ($tipoNovedad->novedadesPreinscritos()->exists()) {
            return back()->withErrors([
                'error' => 'No se puede eliminar un tipo de novedad que tiene novedades asociadas.'
            ]);
        }

        $tipoNovedad->delete();

        return redirect()->route('admin.tipo_novedades.index')
            ->with('success', 'Tipo de novedad eliminado correctamente');
    }
}
