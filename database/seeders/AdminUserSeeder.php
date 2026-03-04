<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'jose.quirozquiroz93@gmail.com'],
            [
                'name' => 'José Quiroz',
                'password' => Hash::make('@JoseQuiroz1304'), // Cambia la contraseña después
            ]
        );
        
        // Asignar rol de Super Admin
        if (!$user->hasRole('Super Admin')) {
            $user->assignRole('Super Admin');
        }
    }
}
