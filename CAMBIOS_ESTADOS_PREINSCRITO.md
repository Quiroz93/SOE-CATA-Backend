# Actualización de Estados de Preinscrito

## Fecha: 2 de Marzo de 2026

### 📋 Resumen de Cambios

Se han actualizado los estados que puede tener un **Preinscrito** en el sistema. Ahora el sistema soporta **5 estados** en lugar de los 3 anteriores.

### 📊 Estados de Preinscrito

| Estado | Valor | Descripción |
|--------|-------|-------------|
| **PENDIENTE** | `pendiente` | Estado inicial cuando se crea un preinscrito |
| **NOVEDAD** | `novedad` | El preinscrito tiene una novedad asociada |
| **PREINSCRITO** | `preinscrito` | El preinscrito ha confirmado su preinscripción |
| **INSCRITO** | `inscrito` | El preinscrito está completamente inscrito |
| **RECHAZADO** | `rechazado` | El preinscrito ha sido rechazado |

### 🔧 Cambios Implementados

#### 1. **Enum EstadoPreinscrito** (Nuevo)
- **Ruta**: `app/Domain/Programa/Enums/EstadoPreinscrito.php`
- Define los 5 estados posibles como valores de enumeración
- Sigue el mismo patrón que `EstadoPrograma`

#### 2. **Migración de Preinscritos**
- **Ruta**: `database/migrations/2026_02_14_180500_create_preinscritos_table.php`
- Cambio: `String 'estado'` → `Enum 'estado'` con los 5 valores permitidos
- Valor por defecto: `'pendiente'`

#### 3. **Modelo Preinscrito**
- **Ruta**: `app/Models/Preinscrito.php`
- Agregado: Import de `EstadoPreinscrito`
- Agregado: Cast de `estado` a `EstadoPreinscrito::class`

#### 4. **Factory PreinscritoFactory**
- **Ruta**: `database/factories/PreinscritoFactory.php`
- Actualizado: Usa los 5 estados correctos (no los antiguos 3)
- Agregado: `tipo_documento` generado aleatoriamente

#### 5. **Controladores**
- **PreinscritoController**: Validaciones actualizadas en `store()` y `update()`
- **PreinscritorImportExportController**: 
  - Estados válidos actualizados en importación
  - Mensajes de validación y instrucciones actualizadas
- **ReporteController**: 
  - Conteo de "aceptados" cambió a contar `whereIn(['preinscrito', 'inscrito'])`
  - Etiqueta cambiada de `'aceptado'` a `'aceptados'`

#### 6. **Vistas**
- **`resources/views/admin/preinscritos/_form.blade.php`**: 
  - Dropdown de estado con los 5 nuevos estados
- **`resources/views/admin/reportes/index.blade.php`**: 
  - Filtro de reportes actualizado con los 5 estados

### ✅ Validación de Estados

Los siguientes valores son **VÁLIDOS** y acepta el sistema:
```
'pendiente'
'novedad'
'preinscrito'
'inscrito'
'rechazado'
```

Cualquier otro valor será **RECHAZADO** por validación.

### 🚀 Antes de la Próxima Migración

Asegúrate de ejecutar:
```bash
php artisan migrate:refresh --seed
```

Esto ejecutará la nueva estructura de enum y los estados serán validados automáticamente por la base de datos.

### 📝 Nota Importante

El estadio de "aceptado" anterior se ha dividido en:
- **PREINSCRITO**: Confirmó la preinscripción
- **INSCRITO**: Completó todo el proceso de inscripción

Esto permite mayor granularidad en el seguimiento del proceso de inscripción.
