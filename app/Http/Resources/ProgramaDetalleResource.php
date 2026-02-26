<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramaDetalleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'detalle' => $this->detalle,
            'multimedia' => $this->multimedia,
            'testimonios' => $this->testimonios,
        ];
    }
}
