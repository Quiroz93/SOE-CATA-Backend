<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Centro>
 */
class CentroFactory extends Factory
{
    /**
     * Estado activo
     */
    public function activo(): static
    {
        return $this->state(fn () => ['estado' => 'activo']);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company(),
            'codigo' => $this->faker->unique()->bothify('CEN###'),
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
        ];
    }
}
