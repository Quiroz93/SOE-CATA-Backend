<?php

declare(strict_types=1);

namespace Tests\Feature\Publico;

use App\Models\Programa;
use App\Domain\Programa\Enums\EstadoPrograma;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramaSoftDeletePublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_no_muestra_programas_soft_deleted(): void
    {
        $publicado = Programa::factory()->create(['estado' => EstadoPrograma::PUBLICADO]);
        $eliminado = Programa::factory()->create(['estado' => EstadoPrograma::PUBLICADO]);
        $eliminado->delete();

        $response = $this->getJson('/api/publico/programas');
        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertTrue($ids->contains($publicado->id));
        $this->assertFalse($ids->contains($eliminado->id));
    }
}
