<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Http\Resources\ProgramaResource;
use App\Http\Resources\ProgramaDetalleResource;
use Illuminate\Http\Request;

class ProgramaPublicoController extends Controller
{
    public function show($slug)
    {
        $programa = Programa::published()->where('slug', $slug)->with(['redesFormacionActivas', 'detalle', 'multimedia', 'testimonios'])->firstOrFail();
        return new ProgramaDetalleResource($programa);
    }
}
