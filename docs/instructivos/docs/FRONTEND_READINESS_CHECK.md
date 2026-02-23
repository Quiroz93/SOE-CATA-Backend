# FRONTEND READINESS CHECK
## Sistema de Gestión de Programas de Formación

Este documento define las condiciones obligatorias que el backend debe cumplir antes de iniciar la implementación del frontend público en Vue 3 + TypeScript.

El objetivo es garantizar estabilidad del contrato API, seguridad, coherencia de datos y alineación con los requerimientos del proyecto.

---

# 1. ARQUITECTURA GENERAL

## 1.1 Separación de responsabilidades

El sistema debe cumplir:

- Backend Laravel centralizado
- Panel administrativo en Blade
- API pública separada de endpoints administrativos
- SPA Vue exclusivamente para vistas públicas
- No exponer rutas /admin en frontend público

Condición:
- Los endpoints públicos deben estar bajo:
  /api/v1/public/*

---

# 2. API PÚBLICA – REQUISITOS OBLIGATORIOS

## 2.1 Endpoints requeridos

Deben existir los siguientes endpoints funcionales:

1. GET /api/v1/public/programas
2. GET /api/v1/public/programas/{id}
3. POST /api/v1/public/preinscripciones

Opcionales recomendados:
- Filtros por municipio
- Filtros por nivel
- Búsqueda por texto
- Paginación

---

## 2.2 Contrato API estandarizado

Todas las respuestas deben tener estructura uniforme:

### Respuesta exitosa

{
  "success": true,
  "message": null,
  "data": {},
  "meta": {}
}

### Error de validación (422)

{
  "success": false,
  "message": "Errores de validación",
  "errors": {}
}

### Error general

{
  "success": false,
  "message": "Mensaje descriptivo",
  "data": null
}

Condiciones:
- No retornar arrays planos sin envoltorio
- No mezclar formatos de respuesta
- No retornar HTML en errores

---

## 2.3 Recursos API (Transformadores)

Debe utilizarse:

- API Resources (Laravel)
- Transformación explícita de atributos
- No exponer columnas internas innecesarias

---

# 3. VALIDACIONES DE NEGOCIO

## 3.1 Preinscripción

Debe validarse:

- Documento obligatorio
- Email válido
- Teléfono obligatorio
- Programa existente
- Oferta activa
- No permitir duplicados por documento + programa
- Validar cupos disponibles

Si falla validación:
- Responder con código 422
- Retornar estructura de errores uniforme

---

# 4. PAGINACIÓN Y FILTROS

El endpoint GET /programas debe:

- Soportar paginación
- Soportar filtros opcionales
- Mantener estructura consistente
- No devolver todos los registros sin control

---

# 5. SEGURIDAD

## 5.1 Separación admin / público

- Endpoints públicos NO deben requerir autenticación
- Endpoints admin deben usar auth:sanctum
- No compartir controladores admin con rutas públicas

---

## 5.2 CORS

- Configuración correcta para permitir consumo desde dominio frontend
- No permitir origenes inseguros en producción

---

# 6. ESTABILIDAD DE CONTRATO

Antes de iniciar frontend:

- No deben existir cambios frecuentes en nombres de campos
- No deben existir endpoints experimentales
- Debe existir versionado activo (/api/v1)

---

# 7. SINCRONIZACIÓN CON HOSTING

Debe estar definido:

- Mecanismo de actualización periódica
- Si la API pública vive en mismo servidor o externo
- Si existe snapshot o consumo directo

Debe existir claridad técnica antes de comenzar Vue.

---

# 8. MANEJO DE ERRORES GLOBAL

Debe existir:

- Handler global para excepciones API
- Respuesta JSON uniforme en 404
- Respuesta JSON uniforme en 500
- No exponer trazas en producción

---

# 9. RENDIMIENTO

Debe verificarse:

- No existencia de N+1 queries
- Uso de eager loading cuando aplique
- Índices en campos clave (ficha, documento)
- Tiempo de respuesta aceptable (<500ms local)

---

# 10. BASE DE DATOS

Debe garantizarse:

- Integridad referencial
- Foreign keys activas
- Unique constraints donde corresponda
- Campos obligatorios definidos correctamente

---

# 11. TESTING MÍNIMO

Debe existir:

- Al menos una prueba feature para:
  - GET programas
  - POST preinscripción
- Validación de errores 422

---

# 12. VARIABLES DE ENTORNO

Debe estar definido:

- URL base API pública
- Dominio permitido CORS
- Entorno desarrollo vs producción

---

# RESULTADO ESPERADO DEL CHECK

Copilot debe:

1. Analizar el proyecto completo.
2. Indicar condición por condición si:
   - Cumple
   - Parcialmente cumple
   - No cumple
3. Señalar archivos relevantes.
4. Sugerir acciones correctivas específicas.

---

# CRITERIO DE INICIO DE FRONTEND

Solo iniciar implementación Vue cuando:

- Todas las condiciones críticas (1, 2, 3, 5, 6) estén completamente cumplidas.
- No existan endpoints inestables.
- El contrato API esté congelado.
