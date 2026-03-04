<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInscritoRequest;
use App\Http\Requests\UpdateInscritoRequest;
use App\Models\Inscrito;
use App\Models\Oferta;
use App\Models\Preinscrito;
use App\Models\Programa;
use Illuminate\Http\Request;

class InscritoController extends Controller
{
    public function index(Request $request)
    {
        $query = Inscrito::with(['preinscrito', 'oferta', 'programa']);

        if ($search = $request->search) {
            $query->whereHas('preinscrito', fn($q) => 
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
            );
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('oferta_id')) {
            $query->where('oferta_id', $request->oferta_id);
        }

        $inscritos = $query->latest()->paginate(15)->withQueryString();
        $ofertas = Oferta::orderBy('nombre')->get();

        return view('admin.inscritos.index', compact('inscritos', 'ofertas'));
    }

    public function create()
    {
        $preinscritos = Preinscrito::whereDoesntHave('inscrito')
            ->orderBy('nombres')
            ->get();
        $ofertas = Oferta::orderBy('nombre')->get();
        $programas = Programa::orderBy('nombre')->get();

        return view('admin.inscritos.create', compact('preinscritos', 'ofertas', 'programas'));
    }

    public function store(StoreInscritoRequest $request)
    {
        Inscrito::create($request->validated());

        return redirect()
            ->route('admin.inscritos.index')
            ->with('success', 'Inscrito creado exitosamente');
    }

    public function show(Inscrito $inscrito)
    {
        $inscrito->load(['preinscrito', 'oferta', 'programa']);

        return view('admin.inscritos.show', compact('inscrito'));
    }

    public function edit(Inscrito $inscrito)
    {
        return view('admin.inscritos.edit', compact('inscrito'));
    }

    public function update(UpdateInscritoRequest $request, Inscrito $inscrito)
    {
        $inscrito->update($request->validated());

        return redirect()
            ->route('admin.inscritos.index')
            ->with('success', 'Inscrito actualizado exitosamente');
    }

    public function destroy(Inscrito $inscrito)
    {
        $inscrito->delete();

        return redirect()
            ->route('admin.inscritos.index')
            ->with('success', 'Inscrito eliminado exitosamente');
    }
}
