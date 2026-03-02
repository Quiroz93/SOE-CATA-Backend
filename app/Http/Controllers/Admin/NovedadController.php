<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Novedad;
use App\Models\TipoNovedad;
use App\Models\Preinscrito;
use Illuminate\Http\Request;

class NovedadController extends Controller
{
    /**
     * Mostrar lista de novedades paginada
     */
    public function index()
    {
        $novedades = Novedad::with(['preinscrito', 'tipoNovedad'])
            ->latest()
            ->paginate(10);

        return view('admin.novedades.index', compact('novedades'));
    }

    /**
     * Mostrar formulario para crear nueva novedad
     */
    public function create()
    {
        $preinscritos = Preinscrito::orderBy('nombre')->get();
        $tiposNovedad = TipoNovedad::orderBy('nombre')->get();

        return view('admin.novedades.create', compact('preinscritos', 'tiposNovedad'));
    }

    /**
     * Almacenar novedad en la base de datos
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'preinscrito_id' => 'required|exists:preinscritos,id',
            'tipo_novedad_id' => 'required|exists:tipos_novedad,id',
            'detalle' => 'nullable|string',
        ], [
            'preinscrito_id.required' => 'El preinscrito es obligatorio',
            'preinscrito_id.exists' => 'El preinscrito seleccionado no es válido',
            'tipo_novedad_id.required' => 'El tipo de novedad es obligatorio',
            'tipo_novedad_id.exists' => 'El tipo de novedad seleccionado no es válido',
        ]);

        Novedad::create($validated);

        return redirect()->route('admin.novedades.index')
            ->with('success', '✅ Novedad registrada exitosamente');
    }

    /**
     * Mostrar detalles de una novedad
     */
    public function show(Novedad $novedad)
    {
        return view('admin.novedades.show', compact('novedad'));
    }

    /**
     * Mostrar formulario para editar novedad
     */
    public function edit(Novedad $novedad)
    {
        $preinscritos = Preinscrito::orderBy('nombre')->get();
        $tiposNovedad = TipoNovedad::orderBy('nombre')->get();

        return view('admin.novedades.edit', compact('novedad', 'preinscritos', 'tiposNovedad'));
    }

    /**
     * Actualizar novedad en la base de datos
     */
    public function update(Request $request, Novedad $novedad)
    {
        $validated = $request->validate([
            'preinscrito_id' => 'required|exists:preinscritos,id',
            'tipo_novedad_id' => 'required|exists:tipos_novedad,id',
            'detalle' => 'nullable|string',
        ], [
            'preinscrito_id.required' => 'El preinscrito es obligatorio',
            'preinscrito_id.exists' => 'El preinscrito seleccionado no es válido',
            'tipo_novedad_id.required' => 'El tipo de novedad es obligatorio',
            'tipo_novedad_id.exists' => 'El tipo de novedad seleccionado no es válido',
        ]);

        $novedad->update($validated);

        return redirect()->route('admin.novedades.index')
            ->with('success', '✅ Novedad actualizada exitosamente');
    }

    /**
     * Eliminar novedad de la base de datos
     */
    public function destroy(Novedad $novedad)
    {
        $novedad->delete();

        return redirect()->route('admin.novedades.index')
            ->with('success', '✅ Novedad eliminada exitosamente');
    }
}
