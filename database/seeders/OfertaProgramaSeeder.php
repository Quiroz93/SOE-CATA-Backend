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

        $ofertas = \App\Models\Oferta::where('estado', true)->pluck('id');
        $programas = \App\Models\Programa::pluck('id');
        $instructores = \App\Models\Instructor::pluck('id');

        // Asegurar que cada oferta activa tenga al menos un programa relacionado
        $faker = \Faker\Factory::create('es_ES');
        foreach ($ofertas as $ofertaId) {
            \Illuminate\Support\Facades\DB::table('oferta_programa')->insert([
                'oferta_id' => $ofertaId,
                'programa_id' => $programas->random(),
                'instructor_id' => $instructores->random(),
                'cupos' => rand(10, 100),
                'modalidad' => $faker->randomElement(['Presencial', 'Virtual', 'Mixta']),
                'estado' => true, // Relación activa
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
