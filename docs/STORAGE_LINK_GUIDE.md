# 📦 Storage Link - Documentación Completa

## 🎯 Resumen

Este documento describe el sistema completo de prevención y reparación de problemas con el enlace simbólico (`symlink`) del almacenamiento en SENA CATA.

---

## ⚠️ El Problema

El error **403 Forbidden** en `http://localhost:8000/storage/...` ocurre cuando:

1. El enlace simbólico está **roto** o no existe
2. `public/storage` no apunta correctamente a `storage/app/public`
3. El servidor no puede acceder a los archivos

### Causas comunes

- Git elimina los symlinks al clonar
- Cambios en permisos de carpetas
- Movimiento de directorios del proyecto
- Actualización de composer/php
- Limpieza de proyecto (`rm -rf public/storage`)

---

## ✅ Soluciones Implementadas

### 1️⃣ Middleware Automático (`CheckStorageLink`)

**Ubicación:** `app/Http/Middleware/CheckStorageLink.php`

Se ejecuta en **cada request** web y verifica/repara automáticamente el link.

**Registrado en:** `app/Http/Kernel.php` (grupo `web`)

**Ventajas:**
- ✅ Detección automática
- ✅ Reparación silenciosa
- ✅ Sin intervención del usuario
- ✅ Compatible con Windows y Linux

### 2️⃣ Comando Artisan (`storage:verify`)

**Ubicación:** `app/Console/Commands/VerifyStorageLink.php`

Comando manual para diagnóstico y reparación con información detallada.

**Uso:**
```bash
# Verificar estado
php artisan storage:verify

# Salida ejemplo:
# 🔍 Verificando storage link...
#   📍 Link: C:\...\public\storage
#   📍 Target: C:\...\storage\app\public
#
# ✅ Storage link VÁLIDO y funcional
```

### 3️⃣ Helper Functions

**Ubicación:** `app/Support/StorageLinkHelper.php`

Conjunto de funciones para usar en el código:

```php
// Verificar si el link es válido
if (verifyStorageLink()) {
    echo "Storage link OK";
}

// Asegurar que existe, reparando si es necesario
ensureStorageLink();

// Obtener estado detallado
$status = getStorageLinkStatus();
echo $status['message']; // ✅ Storage link válido

// Reparar manualmente
repairStorageLink(
    public_path('storage'),
    storage_path('app/public')
);
```

### 4️⃣ Script PowerShell

**Ubicación:** `scripts/diagnose-storage.ps1`

Script interactivo para diagnosticar y reparar desde Windows.

**Uso:**
```powershell
# Verificar estado
.\scripts\diagnose-storage.ps1 -Action check

# Reparar
.\scripts\diagnose-storage.ps1 -Action repair

# Verificación completa
.\scripts\diagnose-storage.ps1 -Action full

# Forzar reparación incluso si está válido
.\scripts\diagnose-storage.ps1 -Action repair -Force
```

---

## 🚀 Cómo Usar

### Caso 1: Error 403 en Producción (AUTO)

El middleware se encargará automáticamente. **No necesitas hacer nada.**

### Caso 2: Error 403 - Reparación Manual

```bash
php artisan storage:verify
```

### Caso 3: Verificar desde Código

```php
// En un controlador o servicio
if (!verifyStorageLink()) {
    ensureStorageLink();
}

// O con información detallada
$status = getStorageLinkStatus();
if (!$status['is_valid']) {
    Log::warning('Storage link está roto', $status);
}
```

### Caso 4: En Script de Deployment

```bash
#!/bin/bash
# deploy.sh

php artisan storage:verify  # Verifica y repara automáticamente
npm run build
php artisan migrate --force
```

---

## 🔧 Configuración

### Variable de Entorno

En `.env`:
```env
FILESYSTEM_DISK=public
```

### Configuración de Sistema de Archivos

En `config/filesystems.php`:
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
    'visibility' => 'public',
    'throw' => false,
],
```

---

## 📊 Flujo de Operación

```
Request HTTP
    ↓
CheckStorageLink Middleware
    ├─ ¿Link válido?
    │   ├─ SÍ → Continuar ✅
    │   └─ NO → Reparar automáticamente → Continuar ✅
    ↓
Controlador/Vista
    ↓
Acceso a archivos en /storage ✅
```

---

## 🧪 Testing

### Verificar que funciona

```bash
# 1. Crear archivo de prueba
echo "test" > storage/app/public/test.txt

# 2. Acceder desde navegador
# http://localhost:8000/storage/test.txt

# 3. Debería descargar o mostrar "test"

# 4. De no funcionar, ejecutar
php artisan storage:verify
```

### Simular un error

```bash
# Windows
Remove-Item -Path "public\storage" -Force -Recurse

# Linux/Mac
rm -rf public/storage

# El middleware lo reparará automáticamente en el próximo request
```

---

## 📋 Checklist de Instalación

- [x] ✅ Middleware `CheckStorageLink` creado en `app/Http/Middleware/`
- [x] ✅ Middleware registrado en `app/Http/Kernel.php`
- [x] ✅ Comando `VerifyStorageLink` creado en `app/Console/Commands/`
- [x] ✅ Helper functions en `app/Support/StorageLinkHelper.php`
- [x] ✅ Helper autoload en `composer.json`
- [x] ✅ Script PowerShell en `scripts/diagnose-storage.ps1`
- [x] ✅ Documentación completa

### Activar las funciones helper

```bash
composer dump-autoload
```

---

## 🔍 Troubleshooting

### Error: "The [public/storage] link already exists"

```bash
# Solución 1: Forzar link
php artisan storage:link --force

# Solución 2: Recrear manualmente
rm -rf public/storage
php artisan storage:link
```

### Error: "The directory already exists"

```bash
# Verificar si es directorio o symlink
ls -lah public/storage

# Si es directorio (no symlink), reparar
php artisan storage:verify
```

### Error: Permission Denied

```bash
# Windows - Ejecutar como Administrador
# Linux/Mac
sudo php artisan storage:link
```

### Los archivos se guardan pero no se pueden descargar

```bash
# 1. Verificar que el link existe
php artisan storage:verify

# 2. Verificar permisos
ls -la storage/app/public

# 3. Cambiar permisos si es necesario (Linux/Mac)
chmod -R 755 storage/app/public
chmod -R 755 storage/app

# 4. En Windows, asegurar que el usuario tiene permisos
```

---

## 📝 Logs

El middleware registra intentos de reparación en:
- `storage/logs/laravel.log`

Busca por: `CheckStorageLink`

---

## 🎓 Referencias

- [Laravel Storage Documentation](https://laravel.com/docs/filesystem)
- [Windows Symbolic Links](https://docs.microsoft.com/en-us/windows/win32/fileio/creating-symbolic-links)
- [Linux Symbolic Links](https://linux.die.net/man/1/ln)

---

## 👥 Contacto y Soporte

Para problemas con el storage link:

1. Ejecutar: `php artisan storage:verify`
2. Revisar: `storage/logs/laravel.log`
3. Si persiste: Ejecutar script diagnóstico

```bash
.\scripts\diagnose-storage.ps1 -Action full
```

---

**Última actualización:** Marzo 3, 2026  
**Version:** 1.0  
**Sistema:** SENA CATA
