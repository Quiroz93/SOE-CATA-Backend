<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramaMultimedia>
 */
class ProgramaMultimediaFactory extends Factory
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
            'tipo' => $this->faker->randomElement(['video', 'imagen', 'documento']),
            'url_externa' => $this->faker->url(),
            'ruta_archivo' => $this->faker->optional()->lexify('media/??????.jpg'),
            'orden' => $this->faker->numberBetween(1, 10),
        ];
    }
}
