<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Programa\Enums\EstadoPreinscrito;
use App\Domain\Programa\Enums\EstadoPrograma;
use App\Models\Preinscrito;
use App\Models\OfertaPrograma;
use App\Models\Programa;
use Illuminate\Http\Request;

class PreinscritoController extends Controller
{
    public function index(Request $request)
    {
        $query = Preinscrito::with(['ofertaPrograma.programa']);

        // Filtro por nombres o apellidos
        if ($request->filled('nombre')) {
            $search = $request->nombre;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('apellido', 'like', '%' . $search . '%');
            });
        }

        // Filtro por documento
        if ($request->filled('documento')) {
            $query->where('documento', 'like', '%' . $request->documento . '%');
        }

        // Filtro por programa
        if ($request->filled('programa_id')) {
            $query->whereHas('ofertaPrograma', function ($q) use ($request) {
                $q->where('programa_id', $request->programa_id);
            });
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $estado = EstadoPreinscrito::tryFromInput((string) $request->estado);
            if ($estado) {
                $query->where('estado', $estado->value);
            }
        }

        // Filtro por correo
        if ($request->filled('correo')) {
            $query->where('correo', 'like', '%' . $request->correo . '%');
        }

        $preinscritos = $query->paginate(15);

        // Obtener programas para el select de filtro
        $programas = Programa::where('estado', EstadoPrograma::PUBLICADO->value)
            ->orderBy('nombre')
            ->get();

        $estados = EstadoPreinscrito::cases();

        return view('admin.preinscritos.index', compact('preinscritos', 'programas', 'estados'));
    }

    public function show(Preinscrito $preinscrito)
    {
        $preinscrito->load(['ofertaPrograma']);
        return view('admin.preinscritos.show', compact('preinscrito'));
    }

    public function create()
    {
        $ofertasPrograma = OfertaPrograma::all();
        $estados = EstadoPreinscrito::cases();
        return view('admin.preinscritos.create', compact('ofertasPrograma', 'estados'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'oferta_id' => 'required|exists:ofertas,id',
            'oferta_programa_id' => 'required|exists:oferta_programa,id',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'tipo_documento' => 'required|in:CC,TI,CE,PAS,PPT',
            'documento' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'estado' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!EstadoPreinscrito::tryFromInput((string) $value)) {
                        $fail('El estado seleccionado no es válido.');
                    }
                },
            ],
        ]);

        $validated['estado'] = EstadoPreinscrito::tryFromInput((string) $validated['estado'])?->value;

        Preinscrito::create($validated);
        
        return redirect()->route('admin.preinscritos.index')
            ->with('success', 'Preinscrito creado correctamente');
    }

    public function edit(Preinscrito $preinscrito)
    {
        $ofertasPrograma = OfertaPrograma::all();
        $estados = EstadoPreinscrito::cases();
        return view('admin.preinscritos.edit', compact('preinscrito', 'ofertasPrograma', 'estados'));
    }

    public function update(Request $request, Preinscrito $preinscrito)
    {
        $validated = $request->validate([
            'oferta_id' => 'required|exists:ofertas,id',
            'oferta_programa_id' => 'required|exists:oferta_programa,id',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'tipo_documento' => 'required|in:CC,TI,CE,PAS,PPT',
            'documento' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'estado' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!EstadoPreinscrito::tryFromInput((string) $value)) {
                        $fail('El estado seleccionado no es válido.');
                    }
                },
            ],
        ]);

        $validated['estado'] = EstadoPreinscrito::tryFromInput((string) $validated['estado'])?->value;

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
