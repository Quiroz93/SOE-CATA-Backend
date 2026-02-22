<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(ProgramaSeeder::class);

        // Crear usuario inicial Super Admin si no existe
        $admin = \App\Models\User::firstOrCreate(
            [ 'email' => 'admin@sistema.local' ],
            [ 'name' => 'Super Admin', 'password' => bcrypt('Admin1234!') ]
        );
        if (!$admin->hasRole('Super Admin')) {
            $admin->assignRole('Super Admin');
        }
    }
}
