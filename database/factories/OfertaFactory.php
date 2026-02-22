<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Oferta>
 */
class OfertaFactory extends Factory
{
    /**
     * Estado abierta
     */
    public function abierta(): static
    {
        return $this->state(fn () => ['estado' => 'abierta']);
    }

    /**
     * Estado cerrada
     */
    public function cerrada(): static
    {
        return $this->state(fn () => ['estado' => 'cerrada']);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'centro_id' => \App\Models\Centro::factory(),
            'nombre' => $this->faker->sentence(3),
            'descripcion' => $this->faker->paragraph(),
            'estado' => $this->faker->randomElement(['abierta', 'cerrada']),
            'fecha_inicio' => $this->faker->date(),
            'fecha_fin' => $this->faker->date(),
        ];
    }
}
