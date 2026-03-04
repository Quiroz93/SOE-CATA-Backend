<?php

namespace Database\Factories;

use App\Domain\Programa\Enums\EstadoPreinscrito;
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
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'tipo_documento' => $this->faker->randomElement(['CC', 'TI', 'CE', 'PAS', 'PPT']),
            'documento' => $this->faker->unique()->numerify('########'),
            'correo' => $this->faker->unique()->safeEmail(),
            'estado' => $this->faker->randomElement([
                EstadoPreinscrito::PENDIENTE->value,
                EstadoPreinscrito::PREINSCRITO->value,
                EstadoPreinscrito::INSCRITO->value,
                EstadoPreinscrito::RECHAZADO->value,
            ]),
        ];
    }
}
