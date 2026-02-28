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
                "slug" => "procesos-de-panaderia",
                "ficha" => "3410523",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TÉCNICO",
                "nombre" => "DIBUJO ARQUITECTÓNICO – FIC",
                "slug" => "dibujo-arquitectonico-fic",
                "ficha" => "3410525",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TÉCNICO",
                "nombre" => "ATENCIÓN INTEGRAL A LA PRIMERA INFANCIA",
                "slug" => "atencion-integral-a-la-primera-infancia",
                "ficha" => "3410527",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TÉCNICO",
                "nombre" => "COSMETOLOGÍA Y ESTÉTICA INTEGRAL",
                "slug" => "cosmetologia-y-estetica-integral",
                "ficha" => "3410528",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TÉCNICO",
                "nombre" => "EJECUCIÓN DE PROGRAMAS DEPORTIVO",
                "slug" => "ejecucion-de-programas-deportivo",
                "ficha" => "3410546",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "ACTIVIDAD FÍSICA",
                "slug" => "actividad-fisica",
                "ficha" => "3410548",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "GESTIÓN ADMINISTRATIVA",
                "slug" => "gestion-administrativa",
                "ficha" => "3410568",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "ANÁLISIS Y DESARROLLO DE SOFTWARE",
                "slug" => "analisis-y-desarrollo-de-software",
                "ficha" => "3410551",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "COORDINACIÓN EN SISTEMAS INTEGRADOS DE GESTIÓN",
                "slug" => "coordinacion-en-sistemas-integrados-de-gestion",
                "ficha" => "3410564",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "GESTIÓN CONTABLE Y DE INFOMACION FINANCIERA",
                "slug" => "gestion-contable-y-de-informacion-financiera",
                "ficha" => "3410558",
                "estado" => EstadoPrograma::PUBLICADO
            ],
            [
                "nivel" => "TECNÓLOGO",
                "nombre" => "LEVATAMIENTOS TOPOGRÁFICOS Y GEORREFERENCIACIÓN –FIC",
                "slug" => "levantamientos-topograficos-y-georreferenciacion-fic",
                "ficha" => "3410569",
                "estado" => EstadoPrograma::PUBLICADO
            ]
        ];

        $faker = \Faker\Factory::create('es_ES');
        foreach ($programas as $item) {
            Programa::updateOrCreate(
                ['ficha' => $item['ficha']],
                [
                    'nivel' => $item['nivel'],
                    'nombre' => $item['nombre'],
                    'slug' => $item['slug'],
                    'estado' => $item['estado'],
                    'modalidad' => $faker->randomElement(['Presencial', 'Virtual', 'Mixta']),
                    'municipio' => $faker->city(),
                ]
            );
        }

        $this->command->info("Seeder de Programas ejecutado correctamente. Se insertaron " . count($programas) . " registros.");
    }
}
