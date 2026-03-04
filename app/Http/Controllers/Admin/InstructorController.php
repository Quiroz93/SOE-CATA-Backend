<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Http\Requests\StoreInstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $query = Instructor::query();

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('perfil_descriptivo', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        $instructores = $query->orderBy('nombre')->paginate(15)->withQueryString();

        return view('admin.instructores.index', compact('instructores'));
    }

    public function show(Instructor $instructore)
    {
        $instructore->load('ofertaProgramas.oferta', 'ofertaProgramas.programa');
        return view('admin.instructores.show', compact('instructore'));
    }

    public function create()
    {
        return view('admin.instructores.create');
    }

    public function store(StoreInstructorRequest $request)
    {
        $data = $request->validated();
        $data['activo'] = $request->has('activo');

        Instructor::create($data);

        return redirect()->route('admin.instructores.index')
            ->with('success', 'Instructor creado exitosamente');
    }

    public function edit(Instructor $instructore)
    {
        return view('admin.instructores.edit', compact('instructore'));
    }

    public function update(UpdateInstructorRequest $request, Instructor $instructore)
    {
        $data = $request->validated();
        $data['activo'] = $request->has('activo');

        $instructore->update($data);

        return redirect()->route('admin.instructores.index')
            ->with('success', 'Instructor actualizado exitosamente');
    }

    public function destroy(Instructor $instructore)
    {
        // Verificar si tiene ofertas programa asociadas
        if ($instructore->ofertaProgramas()->count() > 0) {
            return redirect()->route('admin.instructores.index')
                ->with('error', 'No se puede eliminar el instructor porque tiene ofertas de programas asociadas');
        }

        $instructore->delete();

        return redirect()->route('admin.instructores.index')
            ->with('success', 'Instructor eliminado exitosamente');
    }
}
