<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Http\Resources\ProgramaResource;
use App\Http\Resources\ProgramaDetalleResource;
use Illuminate\Http\Request;

class ProgramaPublicoController extends Controller
{
    public function index(Request $request)
    {
        $query = Programa::published();
        // Filtros opcionales
        if ($nivel = $request->input('nivel')) {
            $query->where('nivel', $nivel);
        }
        if ($nombre = $request->input('nombre')) {
            $query->where('nombre', 'like', "%$nombre%");
        }
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('descripcion', 'like', "%$search%")
                  ->orWhere('ficha', 'like', "%$search%")
                  ;
            });
        }
        $programas = $query->orderByDesc('id')->paginate(10);
        return ProgramaResource::collection($programas);
    }

    public function show($slug)
    {
        $programa = Programa::published()->where('slug', $slug)->with(['redesFormacionActivas', 'detalle', 'multimedia', 'testimonios'])->firstOrFail();
        return new ProgramaDetalleResource($programa);
    }
}
