<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyStorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica y repara automáticamente el enlace simbólico de almacenamiento';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        $this->line('');
        $this->line('🔍 <fg=blue>Verificando storage link...</>');
        $this->line('');

        // Mostrar información
        $this->info("  📍 Link: {$link}");
        $this->info("  📍 Target: {$target}");
        $this->line('');

        // Evaluar estado actual
        $linkExists = is_link($link);
        $dirExists = is_dir($link);
        $targetValid = is_dir($target);

        if (!$targetValid) {
            $this->error('❌ El directorio de destino no existe:');
            $this->error("   {$target}");
            $this->line('');
            $this->warn('⚠️  Crea el directorio manualmente o ejecuta: mkdir -p ' . $target);
            return 1;
        }

        // En Windows, is_link() puede no funcionar correctamente
        // Usar lstat como fallback
        if (PHP_OS_FAMILY === 'Windows' && $dirExists && !$linkExists) {
            $stat = @lstat($link);
            if ($stat && ($stat['mode'] & 0xA000)) {
                // Es un symlink en Windows (atributo especial)
                $linkExists = true;
            }
        }

        if ($linkExists && $dirExists) {
            $this->info('✅ Storage link VÁLIDO y funcional');
            $this->line('');
            return 0;
        }

        if ($linkExists && !is_dir($link)) {
            $this->error('❌ Storage link ROTO (apunta a ubicación inexistente)');
        } elseif ($dirExists && !$linkExists) {
            $this->error('❌ Storage es un DIRECTORIO (no es symlink)');
        } elseif (!$linkExists && !$dirExists) {
            $this->warn('⚠️  Storage link NO EXISTE');
        }

        $this->line('');
        $this->info('💾 Use: php artisan storage:link');
        $this->line('');

        return 0;
    }

    /**
     * Repara el enlace simbólico
     */
    private function repairStorageLink(string $link, string $target): bool
    {
        // Eliminar lo existente sin errores ruidosos
        if (is_link($link)) {
            @unlink($link);
        } elseif (is_dir($link)) {
            @rmdir($link);
        } elseif (is_file($link)) {
            @unlink($link);
        }

        // Crear el novo link
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                // Convertir a rutas Windows y usar realpath para que exista
                $target = str_replace('/', '\\', realpath($target) ?: $target);
                $link = str_replace('/', '\\', $link);
                
                // Asegurar que el link no existe antes de crear
                if (is_dir($link) || is_link($link)) {
                    @rmdir($link);
                    @unlink($link);
                    usleep(100000); // Pequeña pausa
                }
                
                exec("mklink /D \"{$link}\" \"{$target}\" 2>&1", $output, $returnVar);
                
                if ($returnVar !== 0) {
                    $this->error('Error de Windows:');
                    foreach ($output as $line) {
                        $this->line("  {$line}");
                    }
                    return false;
                }
            } else {
                if (!symlink($target, $link)) {
                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            $this->error('Excepción: ' . $e->getMessage());
            return false;
        }
    }
}
