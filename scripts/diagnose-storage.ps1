# SENA CATA - Storage Link Diagnostic Script
# Script para diagnosticar y reparar problemas con el enlace de almacenamiento

param(
    [ValidateSet('check', 'repair', 'full')]
    [string]$Action = 'check',
    
    [switch]$Force = $false
)

function Write-Header {
    Write-Host "`n" -ForegroundColor White
    Write-Host "╔════════════════════════════════════════════════════╗" -ForegroundColor Cyan
    Write-Host "║  SENA CATA - Storage Link Diagnostic Tool          ║" -ForegroundColor Cyan
    Write-Host "╚════════════════════════════════════════════════════╝" -ForegroundColor Cyan
    Write-Host "`n"
}

function Check-StorageLink {
    $link = "public\storage"
    $target = "storage\app\public"
    
    Write-Host "🔍 Verificando Storage Link...`n" -ForegroundColor Blue
    Write-Host "  📍 Link: $link"
    Write-Host "  📍 Target: $target`n"
    
    $linkExists = Test-Path -Path $link -PathType Container
    $linkIsSymlink = (Get-Item $link -Force -ErrorAction SilentlyContinue).LinkType -eq 'SymbolicLink'
    $targetExists = Test-Path -Path $target -PathType Container
    
    Write-Host "Estado del Link:" -ForegroundColor Yellow
    Write-Host "  • Existe: $(if ($linkExists) { '✅ SÍ' } else { '❌ NO' })"
    Write-Host "  • Es Symlink: $(if ($linkIsSymlink) { '✅ SÍ' } else { '❌ NO' })"
    Write-Host "  • Target Existe: $(if ($targetExists) { '✅ SÍ' } else { '❌ NO' })`n"
    
    if ($linkIsSymlink -and $linkExists) {
        Write-Host "✅ Storage link es VÁLIDO y funcional" -ForegroundColor Green
        return $true
    } elseif ($linkExists -and !$linkIsSymlink) {
        Write-Host "❌ Storage es un DIRECTORIO (no symlink) - DEBE REPARARSE" -ForegroundColor Red
        return $false
    } elseif (!$linkExists) {
        Write-Host "⚠️  Storage link NO EXISTE - DEBE CREARSE" -ForegroundColor Yellow
        return $false
    }
    
    return $false
}

function Repair-StorageLink {
    $link = "public\storage"
    $target = "storage\app\public"
    $targetExists = Test-Path -Path $target -PathType Container
    
    if (!$targetExists) {
        Write-Host "❌ Error: El directorio destino no existe: $target" -ForegroundColor Red
        Write-Host "   Por favor, crea el directorio manualmente" -ForegroundColor Yellow
        return $false
    }
    
    Write-Host "🔧 Reparando Storage Link...`n" -ForegroundColor Blue
    
    # Limpiar link existente
    if (Test-Path -Path $link) {
        Write-Host "  • Removiendo enlace existente..." -ForegroundColor Gray
        Remove-Item -Path $link -Force -ErrorAction SilentlyContinue -Recurse | Out-Null
        Start-Sleep -Milliseconds 500
    }
    
    # Convertir rutas a formato Windows
    $linkFull = (Resolve-Path -Path $link -ErrorAction SilentlyContinue) -replace '/',  '\'
    $targetFull = (Resolve-Path -Path $target -ErrorAction SilentlyContinue) -replace '/', '\'
    
    Write-Host "  • Creando nuevo enlace simbólico..." -ForegroundColor Gray
    
    try {
        $output = cmd /c "mklink /D `"$linkFull`" `"$targetFull`" 2>&1"
        $exitCode = $LASTEXITCODE
        
        if ($exitCode -eq 0) {
            Write-Host "  ✅ Enlace simbólico creado" -ForegroundColor Green
            Start-Sleep -Milliseconds 500
            
            # Verificar que funciona
            if ((Get-Item $link -Force -ErrorAction SilentlyContinue).LinkType -eq 'SymbolicLink') {
                Write-Host "  ✅ Verificación completada exitosamente" -ForegroundColor Green
                return $true
            } else {
                Write-Host "  ⚠️  El enlace se creó pero no se verifica como symlink" -ForegroundColor Yellow
                return $false
            }
        } else {
            Write-Host "  ❌ Error al crear el enlace:$output" -ForegroundColor Red
            return $false
        }
    } catch {
        Write-Host "  ❌ Excepción: $_" -ForegroundColor Red
        return $false
    }
}

function Show-Instructions {
    Write-Host "📖 Instrucciones para reparar manualmente:" -ForegroundColor Yellow
    Write-Host "`n1. Abre PowerShell como ADMINISTRADOR`n"
    Write-Host "2. Navega a la carpeta del proyecto:`n"
    Write-Host "   cd `"$((Get-Location).Path)`"`n"
    Write-Host "3. Ejecuta:`n"
    Write-Host "   mklink /D `"public\storage`" `"storage\app\public`"`n"
    Write-Host "4. Verifica que funcione:`n"
    Write-Host "   php artisan storage:verify`n"
}

# Main
Write-Header

Write-Host "Acción: $($Action.ToUpper())" -ForegroundColor Cyan
Write-Host "Forzar: $(if ($Force) { 'SÍ' } else { 'NO' })`n"

$isValid = Check-StorageLink

if ($Action -eq 'check') {
    exit $(if ($isValid) { 0 } else { 1 })
}

if ($Action -eq 'repair' -or $Action -eq 'full') {
    if ($isValid -and !$Force) {
        Write-Host "El storage link ya es válido. Usa -Force para forzar la reparación.`n" -ForegroundColor Green
        exit 0
    }
    
    Write-Host "`n"
    $repaired = Repair-StorageLink
    
    if ($repaired) {
        Write-Host "`n✅ Storage link reparado exitosamente`n" -ForegroundColor Green
        
        if ($Action -eq 'full') {
            Write-Host "Ejecutando: php artisan storage:verify`n" -ForegroundColor Blue
            php artisan storage:verify
        }
        
        exit 0
    } else {
        Write-Host "`n❌ No se pudo reparar el storage link`n" -ForegroundColor Red
        Show-Instructions
        exit 1
    }
}
