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
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'municipio' => $this->municipio ?? null,
            'nivel' => $this->nivel,
            'modalidad' => $this->modalidad ?? null,
            'cupos_disponibles' => $this->cupos ?? null,
            'fecha_inicio' => $this->fecha_inicio ?? null,
            'fecha_fin' => $this->fecha_fin ?? null,
        ];
    }
}
