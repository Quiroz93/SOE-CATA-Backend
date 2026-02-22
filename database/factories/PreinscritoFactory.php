<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Preinscrito>
 */
class PreinscritoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'oferta_id' => \App\Models\Oferta::factory(),
            'oferta_programa_id' => \App\Models\OfertaPrograma::factory(),
            'nombre' => $this->faker->name(),
            'documento' => $this->faker->unique()->numerify('########'),
            'correo' => $this->faker->unique()->safeEmail(),
            'estado' => $this->faker->randomElement(['preinscrito', 'inscrito', 'rechazado']),
        ];
    }
}
