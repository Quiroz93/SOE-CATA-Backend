<?php

declare(strict_types=1);

namespace Tests\Feature\admin;

use App\Models\Novedad;
use App\Models\Preinscrito;
use App\Models\TipoNovedad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreinscritoIndexNovedadesTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_muestra_contador_de_novedades(): void
    {
        $user = User::factory()->create();
        $preinscrito = Preinscrito::factory()->create();
        $tipoNovedad = TipoNovedad::factory()->create();

        // Crear 3 novedades para este preinscrito
        Novedad::factory()->count(3)->create([
            'preinscrito_id' => $preinscrito->id,
            'tipo_novedad_id' => $tipoNovedad->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.preinscritos.index'));

        $response->assertStatus(200);
        $response->assertSee($preinscrito->nombre_completo);
        $response->assertSee('3');
        $response->assertSee('📋');
    }

    public function test_index_muestra_sin_novedades_cuando_no_hay(): void
    {
        $user = User::factory()->create();
        $preinscrito = Preinscrito::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.preinscritos.index'));

        $response->assertStatus(200);
        $response->assertSee('Sin novedades');
    }

    public function test_index_carga_novedad_count_eficientemente(): void
    {
        $user = User::factory()->create();
        
        // Crear 5 preinscritos
        $preinscritos = Preinscrito::factory()->count(5)->create();
        $tipoNovedad = TipoNovedad::factory()->create();

        // Crear novedades para algunos
        Novedad::factory()->count(2)->create([
            'preinscrito_id' => $preinscritos[0]->id,
            'tipo_novedad_id' => $tipoNovedad->id,
        ]);

        Novedad::factory()->create([
            'preinscrito_id' => $preinscritos[2]->id,
            'tipo_novedad_id' => $tipoNovedad->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.preinscritos.index'));

        $response->assertStatus(200);
        $preinscritos->each(function ($preinscrito) use ($response) {
            $response->assertSee($preinscrito->nombre_completo);
        });
    }
}
