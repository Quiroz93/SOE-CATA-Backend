<?php

declare(strict_types=1);

namespace Tests\Feature\admin;

use App\Models\Programa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class ProgramaAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_puede_acceder_a_programas(): void
    {
        $admin = User::factory()->create();
        Role::create(['name' => 'admin']);
        $admin->assignRole('admin');
        $this->actingAs($admin);
        $response = $this->get('/admin/programas');
        $response->assertStatus(200);
    }

    public function test_usuario_sin_permiso_recibe_403(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/admin/programas');
        $response->assertStatus(403);
    }
}
