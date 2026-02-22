<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Programa;
use App\Models\Competencia;
use Illuminate\Support\Facades\DB;

class ProgramaCompetenciaTestSeeder extends Seeder
{
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('competencia_programa')->truncate();
        \App\Models\Competencia::truncate();
        \App\Models\Programa::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $programa = Programa::create([
            'nombre' => 'Programa de Prueba',
            'codigo' => 'PRB001',
            'descripcion' => 'Descripción',
            'estado' => 'publicado',
        ]);

        $competencia = Competencia::create([
            'nombre' => 'Competencia Ética',
            'descripcion' => 'Valores profesionales',
            'area' => 'Ética',
            'estado' => 'publicado',
        ]);

        $programa->competencias()->attach($competencia->id);

        $published = Programa::published()->get();
        dump('Programas publicados:', $published->toArray());
        dump('Competencias asociadas:', $programa->competencias->toArray());
    }
}
