<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Oferta;
use App\Models\Programa;

class OfertaProgramaSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('es_ES');
        
        // Obtener la oferta principal "Primera Oferta 2026-1" (id=1)
        $oferta = Oferta::where('nombre', 'Primera Oferta 2026-1')->first();
        
        if (!$oferta) {
            $this->command->warn('Oferta "Primera Oferta 2026-1" no encontrada. Verificar OfertaSeeder.');
            return;
        }

        // Obtener todos los programas (11 del ProgramaSeeder)
        $programas = Programa::all();
        
        if ($programas->count() === 0) {
            $this->command->warn('No hay programas disponibles. Verificar ProgramaSeeder.');
            return;
        }

        // Centro SENA CATA (id=1)
        $centro = \App\Models\Centro::find(1);
        
        if (!$centro) {
            $this->command->warn('Centro SENA CATA (id=1) no encontrado.');
            return;
        }

        // Relacionar cada programa con la oferta
        foreach ($programas as $programa) {
            DB::table('oferta_programa')->insert([
                'oferta_id' => $oferta->id,
                'programa_id' => $programa->id,
                'instructor_id' => null, // Sin instructor por defecto
                'centro_id' => $centro->id,
                'cupos' => rand(20, 60),
                'modalidad' => $faker->randomElement(['Presencial', 'Virtual', 'Mixta']),
                'estado' => true,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info("Se relacionaron {$programas->count()} programas con la oferta '{$oferta->nombre}'");
    }
}
