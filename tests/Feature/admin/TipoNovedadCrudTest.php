<?php

declare(strict_types=1);

namespace Tests\Feature\admin;

use App\Models\TipoNovedad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TipoNovedadCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_ver_lista_tipos_novedades(): void
    {
        $user = User::factory()->create();
        TipoNovedad::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('admin.tipo-novedad.index'));

        $response->assertOk();
        $response->assertSee('Gestión de Tipos de Novedades');
        $response->assertViewHas('tiposNovedad');
    }

    public function test_usuario_puede_ver_formulario_crear_tipo(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.tipo-novedad.create'));

        $response->assertOk();
        $response->assertSee('Crear Nuevo Tipo de Novedad');
        $response->assertSee('nombre');
        $response->assertSee('descripcion');
    }

    public function test_usuario_puede_crear_tipo_novedad(): void
    {
        $user = User::factory()->create();

        $payload = [
            'nombre' => 'Cambio de Domicilio',
            'descripcion' => 'El preinscrito cambió de domicilio',
        ];

        $response = $this->actingAs($user)->post(route('admin.tipo-novedad.store'), $payload);

        $response->assertRedirect(route('admin.tipo-novedad.index'));
        $this->assertDatabaseHas('tipos_novedad', [
            'nombre' => 'Cambio de Domicilio',
        ]);
    }

    public function test_nombre_tipo_debe_ser_unico(): void
    {
        $user = User::factory()->create();
        TipoNovedad::factory()->create(['nombre' => 'Cambio de Correo']);

        $payload = [
            'nombre' => 'Cambio de Correo',
            'descripcion' => 'Intento duplicado',
        ];

        $response = $this->actingAs($user)->post(route('admin.tipo-novedad.store'), $payload);

        $response->assertSessionHasErrors('nombre');
    }

    public function test_nombre_es_requerido(): void
    {
        $user = User::factory()->create();

        $payload = [
            'nombre' => '',
            'descripcion' => 'Sin nombre',
        ];

        $response = $this->actingAs($user)->post(route('admin.tipo-novedad.store'), $payload);

        $response->assertSessionHasErrors('nombre');
    }

    public function test_usuario_puede_ver_formulario_editar(): void
    {
        $user = User::factory()->create();
        $tipo = TipoNovedad::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.tipo-novedad.edit', $tipo));

        $response->assertOk();
        $response->assertSee('Editar Tipo de Novedad');
        $response->assertSee($tipo->nombre);
    }

    public function test_usuario_puede_actualizar_tipo_novedad(): void
    {
        $user = User::factory()->create();
        $tipo = TipoNovedad::factory()->create(['nombre' => 'Original']);

        $payload = [
            'nombre' => 'Actualizado',
            'descripcion' => 'Nueva descripción',
        ];

        $response = $this->actingAs($user)->put(route('admin.tipo-novedad.update', $tipo), $payload);

        $response->assertRedirect(route('admin.tipo-novedad.index'));
        $this->assertDatabaseHas('tipos_novedad', [
            'id' => $tipo->id,
            'nombre' => 'Actualizado',
        ]);
    }

    public function test_no_permite_eliminar_tipo_con_novedades_asociadas(): void
    {
        $user = User::factory()->create();
        $tipo = TipoNovedad::factory()->create();
        
        // Crear un preinscrito primero
        $preinscrito = \App\Models\Preinscrito::factory()->create();
        
        // Crear una novedad asociada
        $tipo->novedadesPreinscritos()->create([
            'preinscrito_id' => $preinscrito->id,
            'tipo_novedad_id' => $tipo->id,
            'detalle' => 'Test novedad',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.tipo-novedad.destroy', $tipo));

        $response->assertSessionHasErrors('error');
        $this->assertDatabaseHas('tipos_novedad', ['id' => $tipo->id]);
    }

    public function test_usuario_puede_eliminar_tipo_sin_novedades(): void
    {
        $user = User::factory()->create();
        $tipo = TipoNovedad::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.tipo-novedad.destroy', $tipo));

        $response->assertRedirect(route('admin.tipo-novedad.index'));
        $this->assertDatabaseMissing('tipos_novedad', ['id' => $tipo->id]);
    }

    public function test_filtro_por_nombre_funciona(): void
    {
        $user = User::factory()->create();
        TipoNovedad::factory()->create(['nombre' => 'Cambio de Domicilio']);
        TipoNovedad::factory()->create(['nombre' => 'Cambio de Correo']);

        $response = $this->actingAs($user)->get(route('admin.tipo-novedad.index', ['nombre' => 'Domicilio']));

        $response->assertOk();
        $response->assertSee('Cambio de Domicilio');
    }

    public function test_descripcion_es_opcional(): void
    {
        $user = User::factory()->create();

        $payload = [
            'nombre' => 'Tipo Sin Descripción',
            'descripcion' => '',
        ];

        $response = $this->actingAs($user)->post(route('admin.tipo-novedad.store'), $payload);

        $response->assertRedirect(route('admin.tipo-novedad.index'));
        $this->assertDatabaseHas('tipos_novedad', [
            'nombre' => 'Tipo Sin Descripción',
        ]);
    }
}
