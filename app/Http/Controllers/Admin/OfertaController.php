<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\Centro;
use App\Models\Programa;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOfertaRequest;
use Illuminate\Support\Facades\DB;

class OfertaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Oferta::class, 'oferta');
    }

    /**
     * Display a listing of the ofertas
     */
    public function index(Request $request)
    {
        $query = Oferta::with(['centro', 'ofertaProgramas']);

        // Filtro por nombre
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        // Filtro por centro
        if ($request->filled('centro_id')) {
            $query->where('centro_id', $request->centro_id);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->where('fecha_inicio', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_fin', '<=', $request->fecha_hasta);
        }

        $ofertas = $query->latest()->paginate(15);
        $centros = Centro::orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.ofertas.index', compact('ofertas', 'centros'));
    }

    /**
     * Show the form for creating a new oferta
     */
    public function create()
    {
        $centros = Centro::orderBy('nombre')->get(['id', 'nombre']);
        $programas = Programa::where('estado', 'publicado')
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
        $instructores = Instructor::orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.ofertas.create', compact('centros', 'programas', 'instructores'));
    }

    /**
     * Display the specified oferta
     */
    public function show(Oferta $oferta)
    {
        $oferta->load(['centro', 'ofertaProgramas.programa', 'ofertaProgramas.centro', 'ofertaProgramas.instructor']);
        
        return view('admin.ofertas.show', compact('oferta'));
    }
    /**
     * Store a newly created oferta in storage
     */
    public function store(StoreOfertaRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $oferta = Oferta::create(
                $request->only([
                    'centro_id',
                    'nombre',
                    'descripcion',
                    'estado',
                    'fecha_inicio',
                    'fecha_fin',
                ])
            );

            foreach ($request->programas as $programaData) {
                $oferta->ofertaProgramas()->create([
                    'programa_id' => $programaData['programa_id'],
                    'centro_id' => $programaData['centro_id'],
                    'cupos' => $programaData['cupos'],
                    'modalidad' => $programaData['modalidad'],
                    'jornada' => $programaData['jornada'],
                    'municipio' => $programaData['municipio'],
                    'instructor_id' => $programaData['instructor_id'],
                    'estado' => true,
                    'version' => 1,
                ]);
            }

            return redirect()->route('admin.ofertas.index')
                ->with('success', 'Oferta educativa creada correctamente.');
        });
    }
    /**
     * Show the form for editing the specified oferta
     */
    public function edit(Oferta $oferta)
    {
        $oferta->load(['centro', 'ofertaProgramas.programa', 'ofertaProgramas.centro', 'ofertaProgramas.instructor']);
        $centros = Centro::orderBy('nombre')->get(['id', 'nombre']);
        $programas = Programa::where('estado', 'publicado')
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
        $instructores = Instructor::orderBy('nombre')->get(['id', 'nombre']);

        return view('admin.ofertas.edit', compact('oferta', 'centros', 'programas', 'instructores'));
    }
    /**
     * Update the specified oferta in storage
     */
    public function update(StoreOfertaRequest $request, Oferta $oferta)
    {
        return DB::transaction(function () use ($request, $oferta) {
            // 1️⃣ Actualizar datos principales
            $oferta->update(
                $request->only([
                    'centro_id',
                    'nombre',
                    'descripcion',
                    'estado',
                    'fecha_inicio',
                    'fecha_fin',
                ])
            );

            $programasRequest = collect($request->programas);
            $idsRequest = $programasRequest->pluck('id')->filter()->values()->toArray();

            // 2️⃣ Eliminar los que ya no vienen
            $oferta->ofertaProgramas()
                ->whereNotIn('id', $idsRequest)
                ->delete();

            foreach ($programasRequest as $programaData) {
                // 3️⃣ Si existe -> actualizar
                if (!empty($programaData['id'])) {
                    $ofertaPrograma = $oferta->ofertaProgramas()
                        ->where('id', $programaData['id'])
                        ->first();
                    if ($ofertaPrograma) {
                        $ofertaPrograma->update([
                            'programa_id' => $programaData['programa_id'],
                            'centro_id' => $programaData['centro_id'],
                            'cupos' => $programaData['cupos'],
                            'modalidad' => $programaData['modalidad'],
                            'jornada' => $programaData['jornada'],
                            'municipio' => $programaData['municipio'],
                            'instructor_id' => $programaData['instructor_id'],
                        ]);
                    }
                } else {
                    // 4️⃣ Si no existe -> crear nuevo
                    $oferta->ofertaProgramas()->create([
                        'programa_id' => $programaData['programa_id'],
                        'centro_id' => $programaData['centro_id'],
                        'cupos' => $programaData['cupos'],
                        'modalidad' => $programaData['modalidad'],
                        'jornada' => $programaData['jornada'],
                        'municipio' => $programaData['municipio'],
                        'instructor_id' => $programaData['instructor_id'],
                        'estado' => true,
                        'version' => 1,
                    ]);
                }
            }

            return redirect()->route('admin.ofertas.index')
                ->with('success', 'Oferta educativa actualizada correctamente.');
        });
    }
    /**
     * Remove the specified oferta from storage
     */
    public function destroy(Oferta $oferta)
    {
        try {
            // Verificar si tiene preinscritos
            if ($oferta->preinscritos()->exists()) {
                return redirect()->route('admin.ofertas.index')
                    ->with('error', 'No se puede eliminar una oferta que tiene preinscritos asociados.');
            }

            // Eliminar programas asociados
            $oferta->ofertaProgramas()->delete();
            
            // Eliminar la oferta
            $oferta->delete();

            return redirect()->route('admin.ofertas.index')
                ->with('success', 'Oferta eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.ofertas.index')
                ->with('error', 'Error al eliminar la oferta: ' . $e->getMessage());
        }
    }
}
