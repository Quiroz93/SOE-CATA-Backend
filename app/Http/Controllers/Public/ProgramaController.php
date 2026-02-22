<?php

declare(strict_types=1);

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Http\Resources\Publico\ProgramaListResource;
use App\Http\Resources\Publico\ProgramaDetailResource;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class ProgramaController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $page = $request->get('page', 1);
        $perPage = 12;
        $cacheKey = 'programas_publico_index_page_' . $page;
        // Si hay filtros, incluir en la clave
        // $cacheKey .= '_filtros_' . md5(json_encode($request->only(['filtro1', 'filtro2'])));
        $ttl = config('cache.programas_publico_ttl', 60 * 60); // 60 minutos por defecto
        $programas = Cache::remember($cacheKey, $ttl, function () use ($perPage) {
            return Programa::scopePublicado()
                ->select(['id', 'nombre', 'slug', 'estado'])
                ->with(['redesFormacionRelaciones' => function($q) {
                    $q->select(['id', 'programa_id', 'red_formacion_id', 'estado'])
                      ->where('estado', 'activo')
                      ->with(['redFormacion:id,nombre']);
                }])
                ->orderByDesc('id')
                ->paginate($perPage);
        });
        return ProgramaListResource::collection($programas);
    }

    public function show(string $slug): JsonResource
    {
        $cacheKey = 'programa_publico_' . $slug;
        $ttl = config('cache.programas_publico_ttl', 60 * 60); // 60 minutos por defecto
        $programa = Cache::remember($cacheKey, $ttl, function () use ($slug) {
            return Programa::scopePublicado()
                ->select(['id', 'nombre', 'slug', 'descripcion', 'estado'])
                ->with([
                    'redesFormacionRelaciones' => function($q) {
                        $q->select(['id', 'programa_id', 'red_formacion_id', 'estado'])
                          ->where('estado', 'activo')
                          ->with(['redFormacion:id,nombre,descripcion']);
                    },
                    'detalle:id,programa_id,objetivo,perfil_egreso',
                    'multimedia:id,programa_id,url,tipo',
                    'testimonios:id,programa_id,contenido,autor',
                ])
                ->where('slug', $slug)
                ->firstOrFail();
        });
        return new ProgramaDetailResource($programa);
    }
}
