<?php


namespace Database\Factories;

use App\Domain\Programa\Enums\EstadoPrograma;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Programa>
 */
class ProgramaFactory extends Factory
{
    /**
     * Estado publicado
     */

    public function published(): static
    {
        return $this->state(fn () => ['estado' => EstadoPrograma::PUBLICADO]);
    }

    public function draft(): static
    {
        return $this->state(fn () => ['estado' => EstadoPrograma::BORRADOR]);
    }

    public function archived(): static
    {
        return $this->state(fn () => ['estado' => EstadoPrograma::ARCHIVADO]);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->sentence(2),
            'codigo' => $this->faker->unique()->bothify('PRG###'),
            'descripcion' => $this->faker->paragraph(),
            'estado' => $this->faker->randomElement([
                EstadoPrograma::BORRADOR,
                EstadoPrograma::PUBLICADO,
                EstadoPrograma::ARCHIVADO,
            ]),
        ];
    }
}
