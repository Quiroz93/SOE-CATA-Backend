<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Programa;
use App\Domain\Programa\Enums\EstadoPrograma;

class ProgramaSeeder extends Seeder
{
    public function run(): void
    {
        $programas = [
            [
                "nivel" => "OPERARIO",
                "nombre" => "PROCESOS DE PANADERIA",
                "ficha" => "3410523",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TÉCNICO",
                "nombre" => "DIBUJO ARQUITECTÓNICO – FIC",
                "ficha" => "3410525",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TÉCNICO",
                "nombre" => "ATENCIÓN INTEGRAL A LA PRIMERA INFANCIA",
                "ficha" => "3410527",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TÉCNICO",
                "nombre" => "COSMETOLOGÍA Y ESTÉTICA INTEGRAL",
                "ficha" => "3410528",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TÉCNICO",
                "nombre" => "EJECUCIÓN DE PROGRAMAS DEPORTIVO",
                "ficha" => "3410546",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "ACTIVIDAD FÍSICA",
                "ficha" => "3410548",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "GESTIÓN ADMINISTRATIVA",
                "ficha" => "3410568",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "ANÁLISIS Y DESARROLLO DE SOFTWARE",
                "ficha" => "3410551",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "COORDINACIÓN EN SISTEMAS INTEGRADOS DE GESTIÓN",
                "ficha" => "3410564",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "GESTIÓN CONTABLE Y DE INFOMACION FINANCIERA",
                "ficha" => "3410558",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "LEVATAMIENTOS TOPOGRÁFICOS Y GEORREFERENCIACIÓN –FIC",
                "ficha" => "3410569",
                "estado" => EstadoPrograma::PUBLICADO
            ]
        ];

        foreach ($programas as $item) {
            Programa::updateOrCreate(
                ['ficha' => $item['ficha']],
                [
                    'nivel' => $item['nivel'],
                    'nombre' => $item['nombre'],
                    'estado' => $item['estado']
                ]
            );
        }

        $this->command->info("Seeder de Programas ejecutado correctamente. Se insertaron " . count($programas) . " registros.");
    }
}
