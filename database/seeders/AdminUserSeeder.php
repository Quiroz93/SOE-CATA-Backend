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
        User::updateOrCreate(
            ['email' => 'jose.quirozquiroz93@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'), // Cambia la contraseña después
            ]
        );
    }
}
