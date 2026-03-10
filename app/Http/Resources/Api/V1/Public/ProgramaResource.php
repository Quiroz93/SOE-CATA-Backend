<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Public;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para exponer datos públicos de Programa.
 */
class ProgramaResource extends JsonResource
{
    /**
     * Transformar el recurso en un array para respuesta pública.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $ofertaActiva = $this->ofertaProgramas?->firstWhere('estado', true);

        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'descripcion' => $this->descripcion,
            'nivel' => $this->nivel,
            'municipio' => $ofertaActiva?->municipio,
            'estado' => $this->estado,
        ];
    }
}
