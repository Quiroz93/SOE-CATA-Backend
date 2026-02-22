<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RedFormacion>
 */
class RedFormacionFactory extends Factory
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
            'nombre' => $this->faker->unique()->word(),
            'descripcion' => $this->faker->sentence(8),
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
        ];
    }
}
