<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Oferta;

class OfertaSeeder extends Seeder
{
    public function run(): void
    {
        Oferta::factory()->count(5)->create([
            'estado' => true,
        ]);
    }
}
