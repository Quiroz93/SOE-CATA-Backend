<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Http\Resources\OfertaResource;
use App\Http\Resources\OfertaDetalleResource;
use App\Http\Resources\OfertaProgramaResource;
use Illuminate\Http\Request;

class OfertaPublicaController extends Controller
{
    public function index()
    {
        $ofertas = Oferta::activo()->with(['ofertaProgramas' => function($q){ $q->activo()->with(['programa', 'instructor']); }])->get();
        return OfertaResource::collection($ofertas);
    }

    public function show($slug)
    {
        $oferta = Oferta::activo()->where('slug', $slug)->with(['ofertaProgramas' => function($q){ $q->activo()->with(['programa', 'instructor']); }])->firstOrFail();
        return new OfertaDetalleResource($oferta);
    }

    public function programas($slug)
    {
        $oferta = Oferta::activo()->where('slug', $slug)->firstOrFail();
        $programas = $oferta->ofertaProgramas()->activo()->with(['programa', 'instructor'])->get();
        return OfertaProgramaResource::collection($programas);
    }
}
