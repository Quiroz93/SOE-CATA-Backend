<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramaDetalle>
 */
class ProgramaDetalleFactory extends Factory
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
            'nivel_formacion_id' => \App\Models\NivelFormacion::factory(),
            'numero_ficha' => $this->faker->unique()->bothify('FICHA###'),
            'requisitos' => $this->faker->sentence(8),
            'duracion_meses' => $this->faker->numberBetween(6, 36),
            'titulo_otorgado' => $this->faker->sentence(3),
            'codigo_snies' => $this->faker->unique()->bothify('SNIES####'),
            'registro_calidad' => $this->faker->word(),
            'observaciones' => $this->faker->optional()->sentence(6),
        ];
    }
}
