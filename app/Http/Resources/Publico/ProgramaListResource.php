<?php

declare(strict_types=1);

namespace App\Http\Resources\Publico;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramaListResource extends JsonResource
{
    /**
     * @param  \App\Models\Programa  $resource
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'nivel_formacion' => $this->nivelFormacion->nombre ?? null,
            'modalidad' => $this->modalidad ?? null,
            'duracion' => $this->duracion ?? null,
            'imagen_portada' => $this->multimedia->firstWhere('tipo', 'portada')->url ?? null,
            'redes_formacion' => $this->redesFormacionRelaciones
                ? $this->redesFormacionRelaciones->map(fn($rel) => $rel->redFormacion->nombre ?? null)->filter()->values()->all()
                : [],
        ];
    }
}
