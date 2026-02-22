<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateAdminToken extends Command
{
    // Nombre del comando Artisan
    protected $signature = 'admin:token';
    protected $description = 'Genera un token personal para el usuario jose.quirozquiroz93@gmail.com';

    public function handle()
    {
        // Busca el usuario admin por email
        $user = User::where('email', 'jose.quirozquiroz93@gmail.com')->first();

        if (!$user) {
            $this->error('Usuario jose.quirozquiroz93@gmail.com no encontrado.');
            return 1;
        }

        // Genera el token personal
        $token = $user->createToken('API Admin Token')->plainTextToken;

        // Imprime el token listo para usar en Postman
        $this->info("Token generado exitosamente:");
        $this->line($token);
        $this->info("\nCopia este token y pégalo en Postman como Bearer Token.");
        return 0;
    }
}
