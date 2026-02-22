<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Public;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Programa;

class ProgramaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_programas(): void
    {
        Programa::factory()->count(5)->create(['estado' => 'publicado']);
        $response = $this->getJson('/api/v1/public/programas');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta' => ['current_page', 'last_page', 'per_page', 'total']
            ]);
    }

    public function test_can_filter_programas(): void
    {
        Programa::factory()->create(['estado' => 'publicado', 'municipio' => 'A']);
        Programa::factory()->create(['estado' => 'publicado', 'municipio' => 'B']);
        $response = $this->getJson('/api/v1/public/programas?municipio=A');
        $response->assertStatus(200)
            ->assertJsonFragment(['municipio' => 'A'])
            ->assertJsonMissing(['municipio' => 'B']);
    }

    public function test_show_programa_success(): void
    {
        $programa = Programa::factory()->create(['estado' => 'publicado']);
        $response = $this->getJson('/api/v1/public/programas/' . $programa->id);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_show_programa_not_found(): void
    {
        $response = $this->getJson('/api/v1/public/programas/999999');
        $response->assertStatus(404)
            ->assertJson(['success' => false]);
    }
}
