<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Programa\Enums\EstadoPreinscrito;
use App\Domain\Programa\Enums\EstadoPrograma;
use App\Models\User;
use App\Models\Preinscrito;
use App\Models\Oferta;
use App\Models\OfertaPrograma;
use App\Models\Programa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreinscritoController extends Controller
{
    private function canManageHistoricOffers(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $user->hasRole('admin') || $user->hasRole('Super Admin');
    }

    private function validateOfertaByBusinessRule(int $ofertaId, bool $canManageHistoricOffers, ?int $currentOfertaId = null): bool
    {
        if ($canManageHistoricOffers) {
            return true;
        }

        if ($currentOfertaId !== null && $currentOfertaId === $ofertaId) {
            return true;
        }

        return Oferta::where('id', $ofertaId)
            ->where('estado', 'activa')
            ->exists();
    }

    public function index(Request $request)
    {
        $query = Preinscrito::with(['ofertaPrograma.programa'])
            ->withCount('novedades');

        // Filtro por nombres o apellidos
        if ($request->filled('nombre')) {
            $search = $request->nombre;
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'like', '%' . $search . '%')
                  ->orWhere('apellidos', 'like', '%' . $search . '%');
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
        $preinscrito->load([
            'ofertaPrograma',
            'novedades' => function ($query) {
                $query->with('tipoNovedad')->latest();
            }
        ]);
        return view('admin.preinscritos.show', compact('preinscrito'));
    }

    public function create()
    {
        $canManageHistoricOffers = $this->canManageHistoricOffers();

        $ofertasPrograma = OfertaPrograma::with(['oferta:id,nombre', 'programa:id,nombre'])
            ->get(['id', 'oferta_id', 'programa_id']);

        $ofertasQuery = Oferta::query()->orderBy('nombre');
        if (!$canManageHistoricOffers) {
            $ofertasQuery->where('estado', 'activa');
        }

        $ofertas = $ofertasQuery->get(['id', 'nombre', 'estado']);

        $estados = EstadoPreinscrito::cases();
        return view('admin.preinscritos.create', compact('ofertasPrograma', 'ofertas', 'estados', 'canManageHistoricOffers'));
    }

    public function store(Request $request)
    {
        $canManageHistoricOffers = $this->canManageHistoricOffers();

        $validated = $request->validate([
            'oferta_id' => 'required|exists:ofertas,id',
            'oferta_programa_id' => [
                'required',
                'exists:oferta_programa,id',
                function ($attribute, $value, $fail) use ($request) {
                    $ofertaPrograma = OfertaPrograma::find($value);
                    if (!$ofertaPrograma || (int) $ofertaPrograma->oferta_id !== (int) $request->input('oferta_id')) {
                        $fail('La oferta de programa seleccionada no corresponde a la oferta elegida.');
                    }
                },
            ],
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

        if (!$this->validateOfertaByBusinessRule((int) $validated['oferta_id'], $canManageHistoricOffers)) {
            return back()
                ->withErrors([
                    'oferta_id' => 'Solo se permite seleccionar ofertas vigentes (activas).',
                ])
                ->withInput();
        }

        $validated['nombres'] = $validated['nombre'];
        $validated['apellidos'] = $validated['apellido'];
        unset($validated['nombre'], $validated['apellido']);

        $validated['estado'] = EstadoPreinscrito::tryFromInput((string) $validated['estado'])?->value;

        Preinscrito::create($validated);
        
        return redirect()->route('admin.preinscritos.index')
            ->with('success', 'Preinscrito creado correctamente');
    }

    public function edit(Preinscrito $preinscrito)
    {
        $canManageHistoricOffers = $this->canManageHistoricOffers();

        $ofertasPrograma = OfertaPrograma::with(['oferta:id,nombre', 'programa:id,nombre'])
            ->get(['id', 'oferta_id', 'programa_id']);

        $ofertasQuery = Oferta::query()->orderBy('nombre');
        if (!$canManageHistoricOffers) {
            $ofertasQuery->where(function ($query) use ($preinscrito) {
                $query->where('estado', 'activa')
                    ->orWhere('id', $preinscrito->oferta_id);
            });
        }

        $ofertas = $ofertasQuery->get(['id', 'nombre', 'estado']);

        $estados = EstadoPreinscrito::cases();
        return view('admin.preinscritos.edit', compact('preinscrito', 'ofertasPrograma', 'ofertas', 'estados', 'canManageHistoricOffers'));
    }

    public function update(Request $request, Preinscrito $preinscrito)
    {
        $canManageHistoricOffers = $this->canManageHistoricOffers();

        $validated = $request->validate([
            'oferta_id' => 'required|exists:ofertas,id',
            'oferta_programa_id' => [
                'required',
                'exists:oferta_programa,id',
                function ($attribute, $value, $fail) use ($request) {
                    $ofertaPrograma = OfertaPrograma::find($value);
                    if (!$ofertaPrograma || (int) $ofertaPrograma->oferta_id !== (int) $request->input('oferta_id')) {
                        $fail('La oferta de programa seleccionada no corresponde a la oferta elegida.');
                    }
                },
            ],
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

        if (!$this->validateOfertaByBusinessRule((int) $validated['oferta_id'], $canManageHistoricOffers, (int) $preinscrito->oferta_id)) {
            return back()
                ->withErrors([
                    'oferta_id' => 'Solo se permite seleccionar ofertas vigentes (activas).',
                ])
                ->withInput();
        }

        $validated['nombres'] = $validated['nombre'];
        $validated['apellidos'] = $validated['apellido'];
        unset($validated['nombre'], $validated['apellido']);

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
