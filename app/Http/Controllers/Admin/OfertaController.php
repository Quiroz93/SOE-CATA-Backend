<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOfertaRequest;
use Illuminate\Support\Facades\DB;

class OfertaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Oferta::class, 'oferta');
    }

    public function index()
    {
        // ...
    }
    public function show(Oferta $oferta)
    {
        // ...
    }
    public function create()
    {
        // ...
    }
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
                    'modalidad' => $programaData['modalidad'] ?? null,
                    'instructor_id' => $programaData['instructor_id'],
                    'estado' => true,
                    'version' => 1,
                ]);
            }

            return response()->json([
                'message' => 'Oferta educativa creada correctamente',
                'oferta' => $oferta->load('ofertaProgramas.programa'),
            ], 201);
        });
    }
    public function edit(Oferta $oferta)
    {
        // ...
    }
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
                            'modalidad' => $programaData['modalidad'] ?? null,
                            'instructor_id' => $programaData['instructor_id'],
                        ]);
                    }
                } else {
                    // 4️⃣ Si no existe -> crear nuevo
                    $oferta->ofertaProgramas()->create([
                        'programa_id' => $programaData['programa_id'],
                        'centro_id' => $programaData['centro_id'],
                        'cupos' => $programaData['cupos'],
                        'modalidad' => $programaData['modalidad'] ?? null,
                        'instructor_id' => $programaData['instructor_id'],
                        'estado' => true,
                        'version' => 1,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Oferta educativa actualizada correctamente',
                'oferta' => $oferta->load('ofertaProgramas.programa'),
            ]);
        });
    }
    public function destroy(Oferta $oferta)
    {
        // ...
    }
}
