PROMPT PARA AUTOMATIZAR CORRECCIÓN Y ENDURECIMIENTO DEL MONOREPO

Actúa como arquitecto senior Full Stack especializado en:

Laravel 10+

Vue.js 3

Vite

TypeScript

Tailwind CSS

Monorepos profesionales

Contratos OpenAPI/Swagger

CI/CD

Arquitectura limpia

Contexto actual:

Monorepo en Windows.

Backend Laravel modular con API versionada en consolidación.

Carpeta /frontend creada con Vue 3 + Vite + TS.

Node actual: v24 (inestable para el ecosistema actual).

Existen conflictos de dependencias y problemas en vite.config.ts.

Objetivo: estabilizar entorno y endurecer contratos antes de integrar.

🎯 OBJETIVO

Automatizar y ejecutar lo siguiente en orden profesional:

FASE 1 — ESTABILIZAR ENTORNO FRONTEND

Migrar Node a 20 LTS.

Crear archivo .nvmrc con versión 20.

Eliminar:

node_modules

package-lock.json

Reinstalar dependencias limpiamente.

Eliminar dependencias innecesarias como:

path

url

Corregir vite.config.ts usando:

@vitejs/plugin-vue

defineConfig

Alias limpio con fileURLToPath

Verificar que npm run dev funcione sin exit code.

Debe generar:

Comandos exactos

Archivos corregidos

Justificación breve por cada cambio

FASE 2 — ENDURECER CONTRATOS API

En el backend:

Instalar y configurar Swagger/OpenAPI.

Generar documentación versionada /api/v1.

Crear DTOs formales para requests y responses.

Validaciones estrictas.

Exportar openapi.json automáticamente.

Debe generar:

Comandos artisan necesarios

Estructura recomendada

Ejemplo de DTO

Ejemplo de controlador alineado a contrato

FASE 3 — MOCKS AUTOMÁTICOS EN FRONTEND

En /frontend:

Instalar mock layer profesional (MSW o similar).

Generar mocks basados en openapi.json.

Crear carpeta:

/services

/modules

/stores

Crear axios instance con baseURL versionada.

Implementar arquitectura limpia:

service layer

separación dominio/UI

manejo centralizado de errores

Debe generar:

Estructura final de carpetas

Ejemplo real de módulo (auth o users)

Ejemplo de store con Pinia

Ejemplo de service tipado con TypeScript

FASE 4 — VALIDACIÓN CONTRACTUAL EN CI

Generar:

Script para validar contrato en frontend contra openapi.json.

Script npm:

validate:contracts

Ejemplo de workflow CI (GitHub Actions o similar).

📋 FORMATO DE RESPUESTA ESPERADO

La respuesta debe incluir:

Diagnóstico claro

Plan de acción secuencial

Comandos exactos

Archivos completos corregidos

Justificación arquitectónica breve

Riesgos y mitigaciones

No dar explicaciones genéricas.
Actuar como arquitecto senior tomando decisiones firmes.