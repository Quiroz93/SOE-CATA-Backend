<?php

declare(strict_types=1);

namespace Tests\Feature\admin;

use App\Domain\Programa\Enums\EstadoPreinscrito;
use App\Models\Oferta;
use App\Models\OfertaPrograma;
use App\Models\Preinscrito;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PreinscritoOfertaVigenteTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_sin_rol_admin_no_puede_crear_preinscrito_en_oferta_inactiva(): void
    {
        $user = User::factory()->create();

        $ofertaInactiva = Oferta::factory()->create([
            'estado' => 'inactiva',
        ]);

        $ofertaPrograma = OfertaPrograma::factory()->create([
            'oferta_id' => $ofertaInactiva->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('admin.preinscritos.store'), $this->validPayload($ofertaInactiva->id, $ofertaPrograma->id));

        $response->assertSessionHasErrors('oferta_id');
        $this->assertDatabaseCount('preinscritos', 0);
    }

    public function test_admin_si_puede_crear_preinscrito_en_oferta_inactiva(): void
    {
        $admin = User::factory()->create();
        Role::firstOrCreate(['name' => 'admin']);
        $admin->assignRole('admin');

        $ofertaInactiva = Oferta::factory()->create([
            'estado' => 'inactiva',
        ]);

        $ofertaPrograma = OfertaPrograma::factory()->create([
            'oferta_id' => $ofertaInactiva->id,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.preinscritos.store'), $this->validPayload($ofertaInactiva->id, $ofertaPrograma->id));

        $response->assertRedirect(route('admin.preinscritos.index'));
        $this->assertDatabaseHas('preinscritos', [
            'oferta_id' => $ofertaInactiva->id,
            'oferta_programa_id' => $ofertaPrograma->id,
            'documento' => '1234567890',
        ]);
    }

    public function test_usuario_sin_rol_admin_si_puede_crear_preinscrito_en_oferta_activa(): void
    {
        $user = User::factory()->create();

        $ofertaActiva = Oferta::factory()->create([
            'estado' => 'activa',
        ]);

        $ofertaPrograma = OfertaPrograma::factory()->create([
            'oferta_id' => $ofertaActiva->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('admin.preinscritos.store'), $this->validPayload($ofertaActiva->id, $ofertaPrograma->id));

        $response->assertRedirect(route('admin.preinscritos.index'));
        $this->assertDatabaseHas('preinscritos', [
            'oferta_id' => $ofertaActiva->id,
            'oferta_programa_id' => $ofertaPrograma->id,
            'documento' => '1234567890',
        ]);
    }

    public function test_falla_si_programa_no_corresponde_a_la_oferta_seleccionada(): void
    {
        $user = User::factory()->create();

        $ofertaA = Oferta::factory()->create([
            'estado' => 'activa',
        ]);

        $ofertaB = Oferta::factory()->create([
            'estado' => 'activa',
        ]);

        $ofertaProgramaDeB = OfertaPrograma::factory()->create([
            'oferta_id' => $ofertaB->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('admin.preinscritos.store'), $this->validPayload($ofertaA->id, $ofertaProgramaDeB->id));

        $response->assertSessionHasErrors('oferta_programa_id');
        $this->assertDatabaseCount('preinscritos', 0);
    }

    public function test_crea_si_programa_corresponde_a_la_oferta_seleccionada(): void
    {
        $user = User::factory()->create();

        $oferta = Oferta::factory()->create([
            'estado' => 'activa',
        ]);

        $ofertaPrograma = OfertaPrograma::factory()->create([
            'oferta_id' => $oferta->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('admin.preinscritos.store'), $this->validPayload($oferta->id, $ofertaPrograma->id));

        $response->assertRedirect(route('admin.preinscritos.index'));
        $this->assertDatabaseHas('preinscritos', [
            'oferta_id' => $oferta->id,
            'oferta_programa_id' => $ofertaPrograma->id,
            'documento' => '1234567890',
        ]);
    }

    public function test_update_falla_si_programa_no_corresponde_a_la_oferta_seleccionada(): void
    {
        $user = User::factory()->create();

        $ofertaA = Oferta::factory()->create(['estado' => 'activa']);
        $ofertaProgramaDeA = OfertaPrograma::factory()->create(['oferta_id' => $ofertaA->id]);

        $ofertaB = Oferta::factory()->create(['estado' => 'activa']);
        $ofertaProgramaDeB = OfertaPrograma::factory()->create(['oferta_id' => $ofertaB->id]);

        $preinscrito = Preinscrito::factory()->create([
            'oferta_id' => $ofertaA->id,
            'oferta_programa_id' => $ofertaProgramaDeA->id,
        ]);

        $response = $this->actingAs($user)->put(
            route('admin.preinscritos.update', $preinscrito),
            $this->validPayload($ofertaA->id, $ofertaProgramaDeB->id)
        );

        $response->assertSessionHasErrors('oferta_programa_id');
        $this->assertDatabaseHas('preinscritos', [
            'id' => $preinscrito->id,
            'oferta_id' => $ofertaA->id,
            'oferta_programa_id' => $ofertaProgramaDeA->id,
        ]);
    }

    public function test_update_exitoso_si_programa_corresponde_a_la_oferta_seleccionada(): void
    {
        $user = User::factory()->create();

        $ofertaA = Oferta::factory()->create(['estado' => 'activa']);
        $ofertaProgramaInicial = OfertaPrograma::factory()->create(['oferta_id' => $ofertaA->id]);
        $ofertaProgramaNuevo = OfertaPrograma::factory()->create(['oferta_id' => $ofertaA->id]);

        $preinscrito = Preinscrito::factory()->create([
            'oferta_id' => $ofertaA->id,
            'oferta_programa_id' => $ofertaProgramaInicial->id,
        ]);

        $response = $this->actingAs($user)->put(
            route('admin.preinscritos.update', $preinscrito),
            $this->validPayload($ofertaA->id, $ofertaProgramaNuevo->id)
        );

        $response->assertRedirect(route('admin.preinscritos.show', $preinscrito));
        $this->assertDatabaseHas('preinscritos', [
            'id' => $preinscrito->id,
            'oferta_id' => $ofertaA->id,
            'oferta_programa_id' => $ofertaProgramaNuevo->id,
            'documento' => '1234567890',
        ]);
    }

    private function validPayload(int $ofertaId, int $ofertaProgramaId): array
    {
        return [
            'oferta_id' => $ofertaId,
            'oferta_programa_id' => $ofertaProgramaId,
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'tipo_documento' => 'CC',
            'documento' => '1234567890',
            'correo' => 'ana.perez@example.com',
            'estado' => EstadoPreinscrito::PENDIENTE->value,
        ];
    }
}
