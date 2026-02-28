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
        $nombre = $this->faker->unique()->sentence(2);
        return [
            'nombre' => $nombre,
            'slug' => strtolower(str_replace(' ', '-', $nombre)),
            'ficha' => $this->faker->unique()->numerify('#######'), // mínimo 7 dígitos
            'descripcion' => $this->faker->paragraph(),
            'estado' => $this->faker->randomElement([
                EstadoPrograma::BORRADOR,
                EstadoPrograma::PUBLICADO,
                EstadoPrograma::ARCHIVADO,
            ]),
        ];
    }
}
