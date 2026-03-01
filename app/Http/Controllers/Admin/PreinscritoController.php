<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preinscrito;
use App\Models\OfertaPrograma;
use Illuminate\Http\Request;

class PreinscritoController extends Controller
{
    public function index()
    {
        $preinscritos = Preinscrito::with(['ofertaPrograma'])->paginate(15);
        return view('admin.preinscritos.index', compact('preinscritos'));
    }

    public function show(Preinscrito $preinscrito)
    {
        $preinscrito->load(['ofertaPrograma']);
        return view('admin.preinscritos.show', compact('preinscrito'));
    }

    public function create()
    {
        $ofertasPrograma = OfertaPrograma::all();
        return view('admin.preinscritos.create', compact('ofertasPrograma'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'oferta_id' => 'required|exists:ofertas,id',
            'oferta_programa_id' => 'required|exists:oferta_programa,id',
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'estado' => 'required|in:pendiente,aceptado,rechazado',
        ]);

        Preinscrito::create($validated);
        
        return redirect()->route('admin.preinscritos.index')
            ->with('success', 'Preinscrito creado correctamente');
    }

    public function edit(Preinscrito $preinscrito)
    {
        $ofertasPrograma = OfertaPrograma::all();
        return view('admin.preinscritos.edit', compact('preinscrito', 'ofertasPrograma'));
    }

    public function update(Request $request, Preinscrito $preinscrito)
    {
        $validated = $request->validate([
            'oferta_id' => 'required|exists:ofertas,id',
            'oferta_programa_id' => 'required|exists:oferta_programa,id',
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'estado' => 'required|in:pendiente,aceptado,rechazado',
        ]);

        $preinscrito->update($validated);
        
        return redirect()->route('admin.preinscritos.show', $preinscrito)
            ->with('success', 'Preinscrito actualizado correctamente');
    }

    public function destroy(Preinscrito $preinscrito)
    {
        $preinscrito->delete();
        
        return redirect()->route('admin.preinscritos.index')
            ->with('success', 'Preinscrito eliminado correctamente');
    }
}
