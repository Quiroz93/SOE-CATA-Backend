# Solución: Corrupción de Codificación UTF-8 en Archivo Excel

## Problema Reportado

**Síntoma**: Textos con caracteres acentuados como "Anulado Matrícula" se mostraban corruptos (mojibake): "Anulado Matr…cula"

**Ubicación**: Dashboard de Estadísticas - Reporte Individual por Ficha

**Alcance**: Afectaba a todos los archivos Excel que contenían caracteres especiales:
- Estados con tildes: "Convocado Matrícula", "Anulado Matrícula"
- Nombres con acentos: "Ángel", "María", "José"
- Acciones con tildes en otros reportes

## Causa Raíz

La biblioteca PhpSpreadsheet encargada de leer archivos Excel no estaba normalizando la codificación de caracteres antes de procesarlos. Si el archivo Excel tenía:

1. **Codificación mixta**: Algunas celdas en UTF-8, otras en Windows-1252 (CP1252)
2. **Codificación no UTF-8**: Archivo guardado completamente en ISO-8859-1 o Windows-1252
3. **Pérdida de información**: Los caracteres acentuados no se convertían correctamente a UTF-8

Resultado: Los valores de strings se corruían durante la lectura (`mb_strtoupper()` u otras operaciones mb_* fallaban).

## Solución Implementada

### 1. Método `ensureUtf8()` / `ensureUtf8Row()`

Se agregó un método privado en cada clase que procesa Excel que normali​za la codificación:

```php
private function ensureUtf8(array $row): array
{
    return array_map(function ($value) {
        if (!is_string($value)) {
            return $value;
        }

        // Si el valor ya es UTF-8 válido, devolverlo tal cual
        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        // Si no es UTF-8, intentar detectar y convertir la codificación
        $encoding = mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            return mb_convert_encoding($value, 'UTF-8', $encoding);
        }

        // Último recurso: asumir Windows-1252 y convertir
        return mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
    }, $row);
}
```

### 2. Archivos Modificados

#### `app/Application/Statistics/AnalyzeExcelIndividualByFicha.php`
- **Línea 12**: Se normaliza cada fila después de leer el Excel
- **Método agregado**: `ensureUtf8()` (20 líneas)
- **Impacto**: Procesa archivos para "REPORTE DE INSCRIPCIONES INDIVIDUAL POR FICHA"

#### `app/Application/Statistics/AnalyzeExcelByProgram.php`
- **Línea 25**: Normalización de filas después de cargar XML
- **Método agregado**: `ensureUtf8()` (20 líneas)
- **Impacto**: Procesa archivos para "REPORTE DE INSCRIPCIONES GENERALES"

#### `app/Http/Controllers/Admin/PreinscritorImportExportController.php`
- **Línea 410**: Normalización para importación de preinscritos
- **Método agregado**: `ensureUtf8Row()` (20 líneas)
- **Impacto**: Importación de usuarios desde Excel en gestión de preinscritos

### 3. Tests Agregados

Se creó `tests/Feature/CharacterEncodingTest.php` con 3 tests:

1. **`test_accented_characters_are_preserved`**: Valida que nombres con acentos se preservan
2. **`test_matricula_state_is_preserved`**: Verifica que "Anulado Matrícula" se detecta correctamente
3. **`test_no_character_corruption_in_estado_values`**: Confirma ausencia de caracteres corruptos

**Resultado**: Todos los tests pasan ✅

## Flujo de Ejecución

```
1. Usuario sube archivo Excel
2. PhpSpreadsheet carga el archivo (.xlsx o .xls)
3. Cada fila se pasa por ensureUtf8()
   - Detecta la codificación actual
   - Convierte a UTF-8 si es necesario
4. Datos normalizados se procesan como siempre
5. Respuesta JSON se envía con charset UTF-8 correcto
6. Cliente recibe "Anulado Matrícula" correctamente renderizado
```

## Validación

### Tests Ejecutados

```bash
✅ tests/Feature/AnalyzeExcelIndividualByFichaTest.php    (1 test, 9 assertions)
✅ tests/Feature/ConsolidateIndividualFichasTest.php       (8 tests, 37 assertions) 
✅ tests/Feature/CharacterEncodingTest.php               (3 tests, 12 assertions - NEW)
```

**Total**: 12 tests, 58 assertions → ALL PASSING

### Build

```bash
✅ npm run build (Vite) - Sin errores
✅ Compilación exitosa
```

## Commit

```
commit 37bec8b
Author: GitHub Copilot
Date:   <timestamp>

    fix: Normalizar codificación UTF-8 en lectura de archivos Excel
    
    - Agregar método ensureUtf8() en AnalyzeExcelIndividualByFicha
    - Agregar método ensureUtf8Row() en AnalyzeExcelByProgram
    - Agregar método ensureUtf8Row() en PreinscritorImportExportController
    - Crear test CharacterEncodingTest.php para validación
    - Soporta conversiones desde ISO-8859-1 y Windows-1252
    
    Resuelve: Caracteres acentuados no se reconocían (mojibake)
```

## Impacto

### Antes ❌
- "Anulado Matrícula" → "Anulado Matr…cula"
- "Ángel" → "?ngel"
- Gráficos vacíos para estados con caracteres especiales

### Después ✅
- "Anulado Matrícula" → "Anulado Matrícula" 
- "Ángel" → "Ángel"
- Gráficos correctamente poblados con todos los estados
- All Excel import workflows funcionan sin corrupción

## Consideraciones Técnicas

### Función `mb_detect_encoding()`
- Intenta detectar automáticamente la codificación
- Estricto: retorna solo `false` si no detecta (no asumir)
- Lista de candidatos: UTF-8, ISO-8859-1, Windows-1252 (orden importa)

### Función `mb_convert_encoding()`
- Garantiza conversión correcta entre codificaciones
- Requiere que se indique IN y OUT encoding
- Seguro: perderá caracteres ilegibles pero no corrompes

### Función `mb_check_encoding()`
- Valida si una cadena está correctamente codificada
- Usado para evitar conversiones innecesarias

## Próximos Pasos (Opcional)

1. **Configuración Centralizada**: Crear un trait `UtfNormalizable` reutilizable
2. **Logging**: Registrar qué conversiones se realizan (para debugging)
3. **Frontend**: Asegurar que los headers HTTP incluyen `charset=utf-8`
4. **Validación**: Agregar middleware para forzar UTF-8 en todas las respuestas JSON

## Referencias

- PHP: mb_check_encoding(), mb_detect_encoding(), mb_convert_encoding()
- Laravel: Response charset configuration
- PhpOffice\PhpSpreadsheet: Character encoding behavior
- ISO-8859-1 vs Windows-1252 vs UTF-8: Character mapping differences
