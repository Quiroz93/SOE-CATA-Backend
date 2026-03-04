<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckStorageLink
{
    /**
     * Verifica y repara el enlace simbólico de almacenamiento en cada request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->verifyStorageLink();
        return $next($request);
    }

    /**
     * Verifica el estado del enlace simbólico y lo repara si es necesario
     */
    private function verifyStorageLink(): void
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        // Si es un symlink válido, no hacer nada
        if (is_link($link) && is_dir($link)) {
            return;
        }

        // Si no existe como link pero existe como directorio (corrupto),
        // o si no existe en absoluto, recréarlo
        $this->repairStorageLink($link, $target);
    }

    /**
     * Repara el enlace simbólico removiendo lo corrupto y recreando
     */
    private function repairStorageLink(string $link, string $target): void
    {
        // Intentar eliminar lo existente
        if (is_link($link)) {
            @unlink($link);
        } elseif (is_dir($link)) {
            @rmdir($link);
        } elseif (is_file($link)) {
            @unlink($link);
        }

        // Crear el link
        if (PHP_OS_FAMILY === 'Windows') {
            $target = str_replace('/', '\\', realpath($target) ?: $target);
            $link = str_replace('/', '\\', $link);
            
            // Pequeña pausa para asegurar que se liberan recursos
            usleep(100000);
            
            exec("mklink /D \"{$link}\" \"{$target}\" 2>&1");
        } else {
            @symlink($target, $link);
        }
    }
}
