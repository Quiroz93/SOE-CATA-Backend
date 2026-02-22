<?php

declare(strict_types=1);

namespace Tests\Feature\Publico;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Programa;
use App\Domain\Programa\Enums\EstadoPrograma;
use App\Models\NivelFormacion;
use App\Models\ProgramaDetalle;
use App\Models\ProgramaMultimedia;
use App\Models\ProgramaTestimonio;
use App\Models\RedFormacion;
use App\Models\ProgramaRedFormacion;

class ProgramaPublicoTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_devuelve_solo_programas_publicados(): void
    {
        $publicado = Programa::factory()->create(['estado' => EstadoPrograma::PUBLICADO]);
        $noPublicado = Programa::factory()->create(['estado' => EstadoPrograma::BORRADOR]);
        $response = $this->getJson('/api/publico/programas');
        $response->assertOk();
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertTrue($ids->contains($publicado->id));
        $this->assertFalse($ids->contains($noPublicado->id));
    }

    public function test_show_devuelve_200_si_publicado(): void
    {
        $programa = Programa::factory()->create(['estado' => EstadoPrograma::PUBLICADO, 'slug' => 'test-slug']);
        $response = $this->getJson('/api/publico/programas/test-slug');
        $response->assertOk();
        $response->assertJsonFragment(['id' => $programa->id, 'slug' => 'test-slug']);
    }

    public function test_show_devuelve_404_si_no_publicado(): void
    {
        $programa = Programa::factory()->create(['estado' => EstadoPrograma::BORRADOR, 'slug' => 'no-publicado']);
        $response = $this->getJson('/api/publico/programas/no-publicado');
        $response->assertNotFound();
    }

    public function test_estructura_json_coincide_con_resource(): void
    {
        $nivel = NivelFormacion::factory()->create(['nombre' => 'Tecnólogo']);
        $programa = Programa::factory()->create(['estado' => EstadoPrograma::PUBLICADO, 'nivel_formacion_id' => $nivel->id]);
        $response = $this->getJson('/api/publico/programas');
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                ['id', 'nombre', 'slug', 'nivel_formacion', 'modalidad', 'duracion', 'imagen_portada', 'redes_formacion']
            ],
            'links', 'meta'
        ]);
    }

    public function test_paginacion_funciona_correctamente(): void
    {
        Programa::factory()->count(30)->create(['estado' => EstadoPrograma::PUBLICADO]);
        $response = $this->getJson('/api/publico/programas?page=2');
        $response->assertOk();
        $this->assertEquals(2, $response->json('meta.current_page'));
    }

    public function test_cache_no_altera_estructura_respuesta(): void
    {
        $programa = Programa::factory()->create(['estado' => EstadoPrograma::PUBLICADO, 'slug' => 'cache-slug']);
        $response1 = $this->getJson('/api/publico/programas/cache-slug');
        $response2 = $this->getJson('/api/publico/programas/cache-slug');
        $response1->assertOk();
        $response2->assertOk();
        $this->assertEquals($response1->json(), $response2->json());
    }
}
