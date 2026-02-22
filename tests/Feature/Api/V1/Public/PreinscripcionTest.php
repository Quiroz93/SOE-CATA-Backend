<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Public;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Programa;
use App\Models\Preinscrito;

class PreinscripcionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_preinscripcion(): void
    {
        $programa = Programa::factory()->create(['estado' => 'publicado']);
        $payload = [
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'documento' => '123456',
            'email' => 'juan@example.com',
            'telefono' => '123456789',
            'programa_id' => $programa->id,
        ];
        $response = $this->postJson('/api/v1/public/preinscripciones', $payload);
        $response->assertStatus(201)
            ->assertJson(['success' => true]);
    }

    public function test_validation_errors_return_422(): void
    {
        $response = $this->postJson('/api/v1/public/preinscripciones', []);
        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_cannot_duplicate_documento_same_programa(): void
    {
        $programa = Programa::factory()->create(['estado' => 'publicado']);
        $payload = [
            'nombres' => 'Ana',
            'apellidos' => 'Gómez',
            'documento' => '654321',
            'email' => 'ana@example.com',
            'telefono' => '987654321',
            'programa_id' => $programa->id,
        ];
        Preinscrito::factory()->create([
            'documento' => '654321',
            'programa_id' => $programa->id,
        ]);
        $response = $this->postJson('/api/v1/public/preinscripciones', $payload);
        $response->assertStatus(409)
            ->assertJson(['success' => false]);
    }
}
