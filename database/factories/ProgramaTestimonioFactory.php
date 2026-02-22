<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramaTestimonio>
 */
class ProgramaTestimonioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'programa_id' => \App\Models\Programa::factory(),
            'nombre_usuario' => $this->faker->name(),
            'relato' => $this->faker->paragraph(),
            'imagen' => $this->faker->optional()->lexify('testimonios/??????.jpg'),
            'destacado' => $this->faker->boolean(),
        ];
    }
}
