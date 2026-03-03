<?php

namespace Database\Seeders;

use App\Models\Centro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CentroSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Centro::create([
            'nombre' => 'Centro Agroempresarial y Turístico de los Andes',
            'codigo' => '9545',
            'direccion' => 'Carrera 11 # 13 - 13 Barrio Ricaute Málaga - Santander',
            'telefono' => '3000000000',
            'email' => 'ofertassenacata@gmail.com',
            'estado' => 'activo',
        ]);
    }
}
