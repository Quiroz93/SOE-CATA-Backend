<?php

declare(strict_types=1);

namespace Tests\Feature\admin;

use App\Domain\Programa\Enums\EstadoPreinscrito;
use App\Models\Oferta;
use App\Models\OfertaPrograma;
use App\Models\Preinscrito;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreinscritoEstadoNovedadTest extends TestCase
{
    use RefreshDatabase;

    private function createValidPayload($ofertaId, $ofertaProgramaId, $estado = 'Pendiente')
    {
        return [
            'oferta_id' => $ofertaId,
            'oferta_programa_id' => $ofertaProgramaId,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'tipo_documento' => 'CC',
            'documento' => '1234567890',
            'correo' => 'juan@example.com',
            'estado' => $estado,
        ];
    }

    public function test_crea_preinscrito_con_estado_novedad_redirije_a_crear_novedad(): void
    {
        $user = User::factory()->create();
        $oferta = Oferta::factory()->create(['estado' => 'activa']);
        $programa = OfertaPrograma::factory()->create(['oferta_id' => $oferta->id]);

        $response = $this->actingAs($user)->post(route('admin.preinscritos.store'), 
            $this->createValidPayload($oferta->id, $programa->id, 'Novedad')
        );

        // Debe redirigir a la creación de novedad
        $response->assertRedirect();
        $this->assertTrue(str_contains($response->headers->get('Location'), 'admin/novedades/create'));
        
        // Debe incluir el preinscrito_id en la URL
        $createdPreinscrito = Preinscrito::first();
        $this->assertNotNull($createdPreinscrito);
        $this->assertTrue(str_contains(
            $response->headers->get('Location'), 
            'preinscrito_id=' . $createdPreinscrito->id
        ));
    }

    public function test_crea_preinscrito_con_estado_pendiente_redirije_a_lista(): void
    {
        $user = User::factory()->create();
        $oferta = Oferta::factory()->create(['estado' => 'activa']);
        $programa = OfertaPrograma::factory()->create(['oferta_id' => $oferta->id]);

        $response = $this->actingAs($user)->post(route('admin.preinscritos.store'),
            $this->createValidPayload($oferta->id, $programa->id, 'Pendiente')
        );

        // Debe redirigir a la lista
        $response->assertRedirect(route('admin.preinscritos.index'));
        
        // Debe mostrar mensaje de éxito
        $response->assertSessionHas('success', 'Preinscrito creado correctamente');
    }

    public function test_preinscrito_novedad_se_crea_correctamente_en_bd(): void
    {
        $user = User::factory()->create();
        $oferta = Oferta::factory()->create(['estado' => 'activa']);
        $programa = OfertaPrograma::factory()->create(['oferta_id' => $oferta->id]);

        $this->actingAs($user)->post(route('admin.preinscritos.store'),
            $this->createValidPayload($oferta->id, $programa->id, 'Novedad')
        );

        $this->assertDatabaseHas('preinscritos', [
            'documento' => '1234567890',
            'estado' => EstadoPreinscrito::NOVEDAD->value,
        ]);
    }

    public function test_mensaje_info_se_muestra_en_formulario_novedad(): void
    {
        $user = User::factory()->create();
        $oferta = Oferta::factory()->create(['estado' => 'activa']);
        $programa = OfertaPrograma::factory()->create(['oferta_id' => $oferta->id]);

        $response = $this->actingAs($user)->post(route('admin.preinscritos.store'),
            $this->createValidPayload($oferta->id, $programa->id, 'Novedad')
        );

        // Obtener el preinscrito creado
        $preinscrito = Preinscrito::first();
        
        // Verificar que la redirección incluye el preinscrito_id
        $response->assertRedirect();
        $response->assertSessionHas('info');
        
        // Acceder directamente a la ruta de novedad y verificar que muestra el mensaje
        $newResponse = $this->actingAs($user)->get(
            route('admin.novedades.create', ['preinscrito_id' => $preinscrito->id])
        );
        $newResponse->assertSee('Por favor, redacta el detalle de la novedad');
    }

    public function test_formulario_novedad_preselecciona_preinscrito_novedad(): void
    {
        $user = User::factory()->create();
        $oferta = Oferta::factory()->create(['estado' => 'activa']);
        $programa = OfertaPrograma::factory()->create(['oferta_id' => $oferta->id]);

        $response = $this->actingAs($user)->post(route('admin.preinscritos.store'),
            $this->createValidPayload($oferta->id, $programa->id, 'Novedad')
        );

        // Obtener el preinscrito creado
        $preinscrito = Preinscrito::first();
        
        // Seguir la redirección
        $followResponse = $this->actingAs($user)->get(
            route('admin.novedades.create', ['preinscrito_id' => $preinscrito->id])
        );

        // Debe tener el valor preseleccionado
        $followResponse->assertSee('value="' . $preinscrito->id . '"', false);
        $followResponse->assertSee($preinscrito->nombre_completo);
    }

    public function test_crea_preinscrito_inscrito_no_redirije_a_novedad(): void
    {
        $user = User::factory()->create();
        $oferta = Oferta::factory()->create(['estado' => 'activa']);
        $programa = OfertaPrograma::factory()->create(['oferta_id' => $oferta->id]);

        $response = $this->actingAs($user)->post(route('admin.preinscritos.store'),
            $this->createValidPayload($oferta->id, $programa->id, 'Inscrito')
        );

        // Debe redirigir a la lista, NO a novedades
        $response->assertRedirect(route('admin.preinscritos.index'));
    }
}
