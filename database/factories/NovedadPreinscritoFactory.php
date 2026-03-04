<?php

namespace Database\Factories;

use App\Models\Preinscrito;
use App\Models\TipoNovedad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NovedadPreinscrito>
 */
class NovedadPreinscritoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'preinscrito_id' => Preinscrito::factory(),
            'tipo_novedad_id' => TipoNovedad::factory(),
            'detalle' => fake()->optional()->sentence(),
        ];
    }
}
