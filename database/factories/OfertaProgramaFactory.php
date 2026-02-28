<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OfertaPrograma>
 */
class OfertaProgramaFactory extends Factory
{
    /**
     * Estado activo
     */
    public function activo(): static
    {
        return $this->state(fn () => ['estado' => 'activo']);
    }

    /**
     * Estado inactivo
     */
    public function inactivo(): static
    {
        return $this->state(fn () => ['estado' => 'inactivo']);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'oferta_id' => \App\Models\Oferta::factory(),
            'programa_id' => \App\Models\Programa::factory(),
            'instructor_id' => \App\Models\Instructor::factory(),
            'centro_id' => \App\Models\Centro::factory(),
            'cupos' => $this->faker->numberBetween(1, 50),
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
            'fecha_inicio' => $this->faker->date(),
            'fecha_fin' => $this->faker->date(),
            'modalidad' => $this->faker->randomElement(['Presencial', 'Virtual', 'Mixta']),
            'version' => 1,
        ];
    }
}
