# Funcionalidad: Análisis Dinámico de Excel y Generación de Gráficas

## Descripción General

Esta funcionalidad permite a los usuarios cargar cualquier archivo Excel, realizar un análisis inteligente de su contenido, y generar gráficas personalizadas sin depender de un formato específico de archivo.

## Características Principales

### 1. **Análisis Inteligente de Archivos**
- Escaneo automático de todas las hojas del archivo Excel
- Identificación de metadatos (encabezados, títulos, fechas, etc.)
- Detección automática de la fila de encabezados
- Análisis de tipos de datos por columna (numérico o texto)
- Estadísticas por columna (min, max, promedio, suma para datos numéricos)
- Vista previa de datos

### 2. **Proceso Guiado por Pasos**

El sistema guía al usuario a través de 6 pasos:

#### **Paso 1: Cargar Archivo**
- Zona de carga drag & drop
- Validación automática del formato (.xls, .xlsx)
- Límite de 10MB

#### **Paso 2: Análisis de Datos**
- Resumen completo del archivo:
  - Total de hojas, filas y columnas
  - Metadatos encontrados (Nombre del Reporte, Fecha, Hora, etc.)
  - Lista de columnas con tipo de dato, valores únicos
  - Muestra de datos (primeras 5 filas)

#### **Paso 3: Selección de Datos**
- Selección visual de columnas mediante checkboxes
- Asignación de roles:
  - **Columna de Etiquetas**: Para el eje X (nombres, categorías)
  - **Columnas de Valores**: Para el eje Y (datos numéricos)
- Soporte para múltiples series de datos

#### **Paso 4: Configuración de Gráfica**
- Título principal de la gráfica
- Tipo de gráfica:
  - 📊 Barras
  - 📈 Líneas
  - 🥧 Circular (Pie)
  - 🍩 Donut
- Título de tabla de resultados
- Selector de color principal

#### **Paso 5: Vista Previa**
- Muestra los datos seleccionados (primeros 10 registros)
- Resumen de configuración
- Opción de ajustar antes de generar

#### **Paso 6: Resultados**
- Gráfica interactiva con Chart.js
- Tabla de datos completa
- Opciones:
  - 🔄 Crear nueva gráfica
  - 💾 Descargar imagen de la gráfica

## Ejemplo de Uso

### Caso: Reporte de Inscripciones

**Datos del archivo Excel:**
```
Nombre del Reporte: REPORTE_DE_INSCRIPCIONES
Fecha del Reporte: 04-03-2026
Hora del Reporte: 14:54:58

COD_FICHA | PROGRAMA                     | CUPO | INSCRITOS_1 | INSCRITOS_2
3410569   | LEVANTAMIENTOS TOPOGRAFICOS  | 30   | 95          | 0
3410546   | PROGRAMAS DEPORTIVOS         | 30   | 34          | 0
3410558   | GESTION CONTABLE             | 35   | 51          | 0
```

**Proceso:**

1. **Cargar** el archivo Excel
2. **Revisar** el análisis automático que muestra:
   - Metadatos: Nombre, Fecha, Hora del reporte
   - 5 columnas identificadas (COD_FICHA, PROGRAMA, CUPO, etc.)
   - Tipos de datos detectados
3. **Seleccionar** columnas:
   - Etiquetas: PROGRAMA
   - Valores: CUPO, INSCRITOS_1, INSCRITOS_2
4. **Configurar** la gráfica:
   - Título: "Comparativo de Cupos vs Inscritos"
   - Tipo: Barras agrupadas
5. **Revisar** vista previa de datos seleccionados
6. **Generar** y visualizar la gráfica con tabla de resumen

## Arquitectura Técnica

### Backend

#### 1. **Servicio: DynamicExcelAnalyzerService.php**
- `analyzeExcelFile()`: Analiza estructura completa del archivo
- `extractData()`: Extrae datos según selección del usuario
- `identifyStructure()`: Detecta automáticamente la fila de encabezados
- `extractMetadata()`: Extrae información antes de la tabla principal
- `analyzeColumns()`: Analiza tipo y estadísticas de cada columna

#### 2. **Controlador: DynamicChartController.php**
- `analyzeFile()`: Endpoint para análisis inicial
- `extractData()`: Endpoint para extracción de datos seleccionados
- `cleanupTempFiles()`: Limpieza de archivos temporales

#### 3. **Rutas API**
```php
POST /admin/dashboard/dynamic-chart/analyze   // Analizar archivo
POST /admin/dashboard/dynamic-chart/extract   // Extraer datos
POST /admin/dashboard/dynamic-chart/cleanup   // Limpiar temporales
```

### Frontend

#### 1. **Vista: dashboard.blade.php**
- UI con indicador de progreso por pasos
- Formularios dinámicos para cada paso
- Zona de carga drag & drop

#### 2. **JavaScript: dynamic-chart-wizard.js**
- Gestión del estado del wizard
- Comunicación con API backend
- Generación de gráficas con Chart.js
- Navegación entre pasos

#### 3. **Estilos: dashboard.css**
- Diseño responsivo del wizard
- Estilos para indicador de progreso
- Cards de análisis y vista previa
- Animaciones y transiciones

## Ventajas del Sistema

### ✅ **Flexibilidad Total**
- No requiere formato específico de archivo
- Funciona con cualquier estructura de Excel
- Adaptable a diferentes tipos de reportes

### ✅ **Inteligente**
- Detección automática de encabezados
- Clasificación automática de tipos de datos
- Identificación de metadatos

### ✅ **Fácil de Usar**
- Proceso guiado paso a paso
- Interfaz visual intuitiva
- Vista previa antes de generar

### ✅ **Potente**
- Soporte para múltiples series de datos
- Diferentes tipos de gráficas
- Personalización completa

### ✅ **Seguro**
- Validación de archivos
- Límite de tamaño
- Limpieza automática de archivos temporales

## Consideraciones de Seguridad

1. **Validación de Archivos**: Solo acepta .xlsx y .xls
2. **Tamaño Máximo**: 10MB por archivo
3. **Almacenamiento Temporal**: Los archivos se guardan en `storage/app/temp/`
4. **Limpieza Automática**: Los archivos con más de 1 hora son eliminados automáticamente
5. **CSRF Protection**: Todas las peticiones están protegidas con token CSRF

## Mantenimiento

### Directorio de Archivos Temporales
Los archivos cargados se almacenan temporalmente en:
```
storage/app/temp/
```

### Limpieza Manual
Si es necesario limpiar archivos temporales manualmente:
```bash
rm -rf storage/app/temp/*
```

### Logs
Los errores se registran en:
```
storage/logs/laravel.log
```

## Requisitos del Sistema

- **PHP**: >= 8.1
- **Laravel**: >= 11.x
- **Paquetes PHP**:
  - PhpOffice/PhpSpreadsheet
- **JavaScript**:
  - Chart.js (incluido en el proyecto)
- **Navegador**: Moderno con soporte para ES6+

## Mejoras Futuras

1. **Exportación PDF**: Generar reportes en PDF con gráficas
2. **Plantillas Guardadas**: Guardar configuraciones para reutilizar
3. **Gráficas Avanzadas**: Scatter plots, gráficas de área, etc.
4. **Filtros Dinámicos**: Filtrar datos antes de generar gráficas
5. **Comparaciones**: Comparar múltiples archivos
6. **Programación**: Generar reportes automáticamente

## Créditos

Desarrollado para el Sistema SOE-CATA del SENA - Centro Agroempresarial y Turístico de los Andes.

**Fecha**: Marzo 2026
**Versión**: 1.0.0
