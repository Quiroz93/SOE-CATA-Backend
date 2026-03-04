<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Oferta;

class OfertaSeeder extends Seeder
{
    public function run(): void
    {
        Oferta::create([
            'centro_id' => 1, // Centro SENA CATA
            'nombre' => 'Primera Oferta 2026-1',
            'descripcion' => 'Primera oferta presencial y a doistancia 2026-1 SENA Centro Agroempresarial y Turístico de los Andes Malaga Santander',
            'estado' => true,
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addMonths(6),
        ]);
    }
}

