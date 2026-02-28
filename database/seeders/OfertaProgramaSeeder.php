<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfertaProgramaSeeder extends Seeder
{
    public function run()
    {
        // Asegurar que existan ofertas, programas e instructores
        if (\App\Models\Oferta::count() < 5) {
            \App\Models\Oferta::factory()->count(5)->create(['estado' => 'activo']);
        }
        if (\App\Models\Programa::count() < 5) {
            \App\Models\Programa::factory()->count(5)->create();
        }
        if (\App\Models\Instructor::count() < 5) {
            \App\Models\Instructor::factory()->count(5)->create();
        }

        $ofertas = \App\Models\Oferta::pluck('id');
        $programas = \App\Models\Programa::pluck('id');
        $instructores = \App\Models\Instructor::pluck('id');

        // Crear 10 registros de oferta_programa con todos los campos requeridos
        for ($i = 0; $i < 10; $i++) {
            \Illuminate\Support\Facades\DB::table('oferta_programa')->insert([
                'oferta_id' => $ofertas->random(),
                'programa_id' => $programas->random(),
                'instructor_id' => $instructores->random(),
                'cupos' => rand(10, 100),
                'estado' => (rand(0,1) === 1), // 1=activo, 0=inactivo
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
