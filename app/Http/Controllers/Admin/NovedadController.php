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
     * Mostrar lista de novedades paginada con filtros
     */
    public function index(Request $request)
    {
        $query = Novedad::with(['preinscrito', 'tipoNovedad']);

        // Filtro por tipo de novedad
        if ($request->filled('tipo_novedad_id')) {
            $query->where('tipo_novedad_id', $request->tipo_novedad_id);
        }

        // Filtro por nombre del preinscrito
        if ($request->filled('nombre')) {
            $query->whereHas('preinscrito', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->nombre . '%');
            });
        }

        // Filtro por documento del preinscrito
        if ($request->filled('documento')) {
            $query->whereHas('preinscrito', function ($q) use ($request) {
                $q->where('documento', 'like', '%' . $request->documento . '%');
            });
        }

        // Filtro por fecha desde
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        // Filtro por fecha hasta
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $novedades = $query->latest()->paginate(10);

        // Obtener tipos de novedad para el select de filtro
        $tiposNovedad = TipoNovedad::orderBy('nombre')->get();

        return view('admin.novedades.index', compact('novedades', 'tiposNovedad'));
    }

    /**
     * Mostrar formulario para crear nueva novedad
     */
    public function create(Request $request)
    {
        $preinscritos = Preinscrito::orderBy('nombre')->get();
        $tiposNovedad = TipoNovedad::orderBy('nombre')->get();
        $preinscritoIdPreseleccionado = $request->query('preinscrito_id');

        return view('admin.novedades.create', compact('preinscritos', 'tiposNovedad', 'preinscritoIdPreseleccionado'));
    }

    /**
     * Almacenar novedad en la base de datos
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'preinscrito_id' => 'required|exists:preinscritos,id',
            'tipo_novedad_id' => 'required|exists:tipos_novedad,id',
            'detalle' => 'nullable|string|max:1000',
        ], [
            'preinscrito_id.required' => 'El preinscrito es obligatorio',
            'preinscrito_id.exists' => 'El preinscrito seleccionado no es válido o no existe',
            'tipo_novedad_id.required' => 'El tipo de novedad es obligatorio',
            'tipo_novedad_id.exists' => 'El tipo de novedad seleccionado no es válido o no existe',
            'detalle.max' => 'El detalle no puede exceder 1000 caracteres',
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
            'detalle' => 'nullable|string|max:1000',
        ], [
            'preinscrito_id.required' => 'El preinscrito es obligatorio',
            'preinscrito_id.exists' => 'El preinscrito seleccionado no es válido o no existe',
            'tipo_novedad_id.required' => 'El tipo de novedad es obligatorio',
            'tipo_novedad_id.exists' => 'El tipo de novedad seleccionado no es válido o no existe',
            'detalle.max' => 'El detalle no puede exceder 1000 caracteres',
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
