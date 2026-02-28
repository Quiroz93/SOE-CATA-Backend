<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfertaResource extends JsonResource
{
    public function toArray($request)
    {
        // Devolver todos los programas relacionados con sus datos
        $programas = $this->ofertaProgramas()->activo()->get()->map(function($ofertaPrograma) {
                $programa = $ofertaPrograma->programa;
                return [
                    'cupos' => $ofertaPrograma->cupos,
                    'modalidad' => $ofertaPrograma->modalidad ?? $programa->modalidad ?? null,
                    'municipio' => $programa->municipio ?? null,
                    'programa' => new \App\Http\Resources\ProgramaResource($programa),
                    'instructor' => new \App\Http\Resources\InstructorResource($ofertaPrograma->instructor),
                ];
        });
            return [
                'id' => $this->id,
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'fecha_inicio' => optional($this->fecha_inicio)->format('Y-m-d'),
                'fecha_fin' => optional($this->fecha_fin)->format('Y-m-d'),
                'estado' => (int) $this->estado,
                'programas' => $programas,
            ];
    }
}
