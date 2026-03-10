<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfertaProgramaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cupos' => $this->cupos,
            'modalidad' => $this->modalidad,
            'jornada' => $this->jornada,
            'municipio' => $this->municipio,
            'centro' => $this->centro_id,
            'instructor' => new InstructorResource($this->instructor),
            'programa' => new ProgramaResource($this->programa),
        ];
    }
}
