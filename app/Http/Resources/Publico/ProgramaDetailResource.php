<?php

declare(strict_types=1);

namespace App\Http\Resources\Publico;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramaDetailResource extends JsonResource
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
            'nivel_formacion' => $this->nivelFormacion ? [
                'id' => $this->nivelFormacion->id,
                'nombre' => $this->nivelFormacion->nombre,
            ] : null,
            'detalle' => $this->detalle ? [
                'objetivo' => $this->detalle->objetivo,
                'perfil_egreso' => $this->detalle->perfil_egreso,
            ] : null,
            'multimedia' => $this->multimedia ? $this->multimedia->map(fn($m) => [
                'tipo' => $m->tipo,
                'url' => $m->url,
            ])->all() : [],
            'testimonios' => $this->testimonios ? $this->testimonios->map(fn($t) => [
                'contenido' => $t->contenido,
                'autor' => $t->autor,
            ])->all() : [],
            'instructores' => $this->instructores ? $this->instructores->map(fn($i) => [
                'id' => $i->id,
                'nombre' => $i->nombre,
                'especialidad' => $i->especialidad ?? null,
            ])->all() : [],
            'redes_formacion' => $this->redesFormacionRelaciones ? $this->redesFormacionRelaciones->map(fn($rel) => [
                'nombre' => $rel->redFormacion->nombre ?? null,
                'fecha_asignacion' => $rel->fecha_asignacion,
            ])->filter(fn($r) => $r['nombre'])->values()->all() : [],
        ];
    }
}
