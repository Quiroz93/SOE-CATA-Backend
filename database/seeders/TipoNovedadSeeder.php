<?php

namespace Database\Seeders;

use App\Models\TipoNovedad;
use Illuminate\Database\Seeder;

class TipoNovedadSeeder extends Seeder
{
    public function run(): void
    {
        $tiposNovedad = [
            [
                'nombre' => 'Cambio de Programa',
                'descripcion' => 'El preinscrito solicita cambio a otro programa de formación',
            ],
            [
                'nombre' => 'Cancelación de Inscripción',
                'descripcion' => 'El preinscrito desea cancelar su inscripción',
            ],
            [
                'nombre' => 'Cambio de Horario',
                'descripcion' => 'Cambio de horario de clases solicitado por el preinscrito',
            ],
            [
                'nombre' => 'Cambio de Centro de Formación',
                'descripcion' => 'El preinscrito solicita cambio de centro de formación',
            ],
            [
                'nombre' => 'Documentación Incompleta',
                'descripcion' => 'El preinscrito no ha completado la documentación requerida',
            ],
            [
                'nombre' => 'Información de Contacto Actualizada',
                'descripcion' => 'El preinscrito actualizó su teléfono, correo o dirección',
            ],
            [
                'nombre' => 'Certificado de Requisitos Previos',
                'descripcion' => 'El preinscrito proporcionó certificado de estudios previos',
            ],
            [
                'nombre' => 'Certificado Médico Requerido',
                'descripcion' => 'El preinscrito proporcionó certificado médico de aptitud',
            ],
            [
                'nombre' => 'Justificante de Inasistencia',
                'descripcion' => 'El preinscrito presentó justificante de ausencia a sesiones de formación',
            ],
            [
                'nombre' => 'Incidente Disciplinario',
                'descripcion' => 'Registro de incidente o falta disciplinaria del preinscrito',
            ],
        ];

        foreach ($tiposNovedad as $tipo) {
            TipoNovedad::firstOrCreate(
                ['nombre' => $tipo['nombre']],
                ['descripcion' => $tipo['descripcion']]
            );
        }
    }
}
