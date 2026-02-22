<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramaRedFormacion>
 */
class ProgramaRedFormacionFactory extends Factory
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
            'red_formacion_id' => \App\Models\RedFormacion::factory(),
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
            'fecha_asignacion' => $this->faker->date(),
            'fecha_desasignacion' => $this->faker->optional()->date(),
            'usuario_asigno_id' => \App\Models\User::factory(),
            'usuario_modifico_id' => \App\Models\User::factory(),
            'observaciones' => $this->faker->optional()->sentence(8),
        ];
    }
}
