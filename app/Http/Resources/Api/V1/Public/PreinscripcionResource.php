<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Public;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para exponer datos públicos de Preinscripción.
 */
class PreinscripcionResource extends JsonResource
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
            'programa_id' => $this->programa_id,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'email' => $this->email,
            'estado' => $this->estado ?? null,
            'fecha_registro' => $this->created_at ? $this->created_at->toDateTimeString() : null,
        ];
    }
}
