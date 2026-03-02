# 📋 Rastreo Completo: Separación de Nombre y Apellido en Preinscritos

## Fecha: 2 de Marzo de 2026

### 🎯 Objetivo
Separar el campo `nombre` en dos campos: `nombre` y `apellido` en la tabla de preinscritos para mayor precisión en la gestión de datos.

---

## 📊 Archivos Afectados

### 1️⃣ **MIGRACIONES**
- **Archivo**: `database/migrations/2026_02_14_180500_create_preinscritos_table.php`
- **Cambio**: 
  - Remover: `$table->string('nombre');`
  - Agregar: `$table->string('nombre');` + `$table->string('apellido');`

### 2️⃣ **MODELOS**
- **Archivo**: `app/Models/Preinscrito.php`
- **Cambio**: Actualizar `$fillable` para incluir `'apellido'`

### 3️⃣ **FACTORIES**
- **Archivo**: `database/factories/PreinscritoFactory.php`
- **Cambio**: 
  - Cambiar: `'nombre' => fake()->name()`
  - Por: `'nombre' => fake()->firstName()` + `'apellido' => fake()->lastName()`

### 4️⃣ **CONTROLADORES ADMIN**

#### a) `app/Http/Controllers/Admin/PreinscritoController.php`
- **Línea 18**: Filtro actualizado para buscar en ambos campos
- **Línea 73**: Validación en `store()` - separar nombre y apellido
- **Línea 98**: Validación en `update()` - separar nombre y apellido

#### b) `app/Http/Controllers/Admin/PreinscritorImportExportController.php`
- **Línea 29-30**: Cambiar referencia de pluck('nombre') a new formula de nombre+apellido
- **Línea 128**: Headers: "Nombre Completo" → "Nombre" y "Apellido"
- **Línea 144**: Configurar columnas para que tengan espacio para nombre y apellido
- **Línea 312**: Instrucciones actualizadas
- **Línea 410**: Procesamiento de import - parsear nombre y apellido
- **Línea 428**: Validación de campos

### 5️⃣ **CONTROLADORES API**

#### a) `app/Http/Controllers/Api/V1/Public/PreinscripcionController.php`
- **Línea 34**: Duplicado check con documento (sin cambios en lógica)

#### b) `app/Http/Requests/StorePreinscripcionRequest.php`
- **Cambio**: Actualizar rules() para validar nombre y apellido separados

### 6️⃣ **VISTAS ADMIN**

#### a) `resources/views/admin/preinscritos/_form.blade.php`
- **Línea 26-30**: Reemplazar input único "Nombre" por dos inputs: "Nombre" y "Apellido"

#### b) `resources/views/admin/preinscritos/index.blade.php`
- **Línea 40-50**: Filtro de nombre actualizado (o mantener como está)
- **Línea 75**: Columna "Nombre" mostrará "Nombre Apellido"
- **Línea 115**: Tabla mostrará nombre y apellido concatenados

#### c) `resources/views/admin/preinscritos/show.blade.php`
- **Línea 18-20**: Mostrar nombre y apellido por separado

### 7️⃣ **RECURSOS DE API**

#### a) `app/Http/Resources/Api/V1/Public/PreinscripcionResource.php`
- **Cambio**: Si retorna `estado`, verificar si también retorna nombre (revisar)

---

## 🔄 FLUJOS AFECTADOS

### ✅ Flujo 1: Creación Manual
```
Vista (_form.blade.php) → Dos inputs (nombre, apellido) 
  → PreinscritoController@store 
  → Validar nombre y apellido 
  → Guardar en BD
```

### ✅ Flujo 2: Edición Manual
```
Vista (edit.blade.php con _form.blade.php) → Dos inputs pre-llenados 
  → PreinscritoController@update 
  → Validar nombre y apellido 
  → Actualizar en BD
```

### ✅ Flujo 3: Listado
```
Vista (index.blade.php) → Tabla muestra "Nombre Apellido" 
  → Filtro busca en ambos campos (opcional)
```

### ✅ Flujo 4: Importación Excel
```
PreinscritorImportExportController@downloadTemplate 
  → Genera Excel con columnas "Nombre" y "Apellido" 
  → Usuario llena ambas columnas 
  → PreinscritorImportExportController@handleImport 
  → Procesa nombre y apellido separados
```

### ✅ Flujo 5: API Pública
```
POST /api/v1/public/preinscripcion 
  → StorePreinscripcionRequest valida nombre y apellido 
  → PreinscripcionController@store 
  → Crear Preinscrito
```

### ✅ Flujo 6: Reportes
```
ReporteController 
  → Si usa nombre en reportes, actualizar para mostrar nombre + apellido
```

---

## ✅ CHECKLIST DE CAMBIOS

### Base de Datos
- [ ] Migración: Agregar campo `apellido`
- [ ] Migración: Asegurar constraints y tipos correctos

### Modelos
- [ ] Modelo: Actualizar `$fillable`
- [ ] Modelo: Agregar trait si es necesario (Ej: casteos)

### Factories
- [ ] Factory: Generar nombre y apellido correctamente

### Controladores
- [ ] PreinscritoController: Validaciones actualizadas
- [ ] PreinscritorImportExportController: Headers y procesamiento
- [ ] StorePreinscripcionRequest: Validaciones

### Vistas
- [ ] Form: Dos inputs para nombre y apellido
- [ ] Index: Mostrar nombre y apellido concatenados
- [ ] Show: Mostrar nombre y apellido separados

### API / Resources
- [ ] PreinscripcionResource: Retornar nombre y apellido

### Testing (Opcional)
- [ ] Actualizar tests que usen el modelo Preinscrito
- [ ] Test de validaciones
- [ ] Test de import/export

---

## ⚠️ CONSIDERACIONES ESPECIALES

1. **Datos Históricos**: Si hay preinscritos en BD, hay que decidir cómo migrar los nombres existentes a los dos campos
2. **Búsqueda**: El filtro por nombre debe buscar en ambos campos para mantener usabilidad
3. **Reports**: Verificar si ReporteController accede al campo nombre
4. **Excel Export**: Headers y columnas deben ser consistentes
5. **Validación**: El campo `tipo_documento` ya existía, no cambiar

---

## 📝 ORDEN DE EJECUCIÓN RECOMENDADO

1. Crear migración nueva (AddNombreApellidoColumnToPreinscritosTable)
2. Actualizar Modelo Preinscrito
3. Actualizar Factory
4. Actualizar Controladores
5. Actualizar Requests
6. Actualizar Vistas
7. Ejecutar migraciones: `php artisan migrate`
8. Ejecutar seed: `php artisan migrate:refresh --seed` (si se desea reiniciar datos)
9. Pruebas manuales

