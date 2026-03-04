<?php

/**
 * SENA CATA - Storage Link Helpers
 * Funciones de utilidad para verificar y reparar el enlace de almacenamiento
 */

if (!function_exists('verifyStorageLink')) {
    /**
     * Verifica que el storage link exista y sea válido
     * 
     * @return bool
     */
    function verifyStorageLink(): bool
    {
        $link = public_path('storage');
        return is_link($link) && is_dir($link);
    }
}

if (!function_exists('ensureStorageLink')) {
    /**
     * Asegura que el storage link exista y sea válido, reparándolo si no lo está
     * 
     * @return bool Retorna true si el link está válido después de intentar repararlo
     */
    function ensureStorageLink(): bool
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        // Si ya existe un link válido, no hacer nada
        if (is_link($link) && is_dir($link)) {
            return true;
        }

        // Intentar reparar
        return repairStorageLink($link, $target);
    }
}

if (!function_exists('repairStorageLink')) {
    /**
     * Repara el enlace simbólico de almacenamiento
     * 
     * @param string $link Ruta del link
     * @param string $target Ruta destino
     * @return bool
     */
    function repairStorageLink(string $link, string $target): bool
    {
        // Validar que el destino existe
        if (!is_dir($target)) {
            return false;
        }

        // Remove corrupted link/directory
        if (is_link($link)) {
            @unlink($link);
        } elseif (is_dir($link)) {
            @rmdir($link);
        } elseif (is_file($link)) {
            @unlink($link);
        }

        // Crear link
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $target = str_replace('/', '\\', $target);
                $link = str_replace('/', '\\', $link);
                exec("mklink /D \"{$link}\" \"{$target}\" 2>&1", $output, $returnVar);
                return $returnVar === 0;
            } else {
                return @symlink($target, $link) !== false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('getStorageLinkStatus')) {
    /**
     * Obtiene información detallada del estado del storage link
     * 
     * @return array
     */
    function getStorageLinkStatus(): array
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        $isLink = is_link($link);
        $isDir = is_dir($link);
        $targetExists = is_dir($target);
        $isValid = $isLink && $isDir;

        return [
            'link_path' => $link,
            'target_path' => $target,
            'is_symlink' => $isLink,
            'is_directory' => $isDir,
            'target_exists' => $targetExists,
            'is_valid' => $isValid,
            'status' => $isValid ? 'valid' : ($targetExists ? 'broken' : 'missing_target'),
            'message' => $isValid 
                ? '✅ Storage link válido'
                : ($targetExists 
                    ? '❌ Storage link corrupto (no es symlink)'
                    : '⚠️  Directorio destino no existe'),
        ];
    }
}
