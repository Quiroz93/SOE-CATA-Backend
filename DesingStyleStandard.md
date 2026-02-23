# 🟢 SENA Admin UI Guidelines & AI Context (2024–2025)

## 1. 📌 Contexto General

Este documento define las especificaciones técnicas, visuales y de experiencia de usuario para el desarrollo de interfaces administrativas y publicas del sistema de gestión y publicación de ofertas educativas del SENA.

Está optimizado para ser utilizado como contexto en asistentes de IA dentro de VS Code (Copilot, ChatGPT, Cursor, etc.), con el fin de automatizar la generación de vistas admin y publicas coherentes con la identidad institucional.

---

## 2. 🏛️ Identidad Institucional

El SENA es una entidad pública colombiana. Su identidad visual está regulada por:

* Manual de Identidad Visual SENA (versión reciente)
* Ley 2345 de 2023 (unificación de imagen estatal)

El uso correcto de la identidad garantiza coherencia, reconocimiento y cumplimiento legal. ([sena.edu.co][1])

---

## 3. 🎨 Sistema de Colores

### 🔰 Color Principal (OBLIGATORIO)

* Verde SENA:

  * HEX: `#39A900`
  * RGB: `57, 169, 0`
  * CMYK: `75, 0, 100, 0`

✔ Uso:

* Botones primarios
* Navbar
* Elementos activos
* Indicadores de estado positivo

---

### 🎯 Colores Secundarios

| Color              | HEX       | Uso                 |
| ------------------ | --------- | ------------------- |
| Verde oscuro       | #007832 | Hover, headers      |
| Azul institucional | #00304D | Sidebar, contrastes |
| Blanco             | #FFFFFF | Fondos              |
| Negro              | #000000 | Texto principal     |

✔ No alterar tonalidades sin justificación.

---

## 4. 🔤 Tipografía

### Tipografía recomendada:

* Sans-serif moderna (ej: Inter, Roboto, Open Sans)

### Reglas:

* Alta legibilidad
* Jerarquía clara (H1 > H2 > H3)
* Evitar fuentes decorativas

---

## 5. 🧩 Componentes UI (Admin Panel)

### 🧱 Layout Base

* Sidebar izquierda (navegación)
* Topbar (usuario, notificaciones)
* Content area (dinámico)

---

### 📋 Componentes clave

#### Botones

* Primario: Verde SENA
* Secundario: Outline o gris
* Peligro: Rojo (#D32F2F)

#### Tablas

* Paginación obligatoria
* Filtros por columna
* Búsqueda en tiempo real

#### Formularios

* Labels claros
* Validación inline
* Estados: success, error, warning

#### Cards

* Para métricas (dashboard)
* Sombras suaves
* Bordes redondeados (8px)

---

## 6. 🧠 UX / UI (Enfoque Gobierno)

### Principios:

* Simplicidad
* Accesibilidad (WCAG 2.1)
* Claridad institucional
* Bajo ruido visual

### Reglas:

* No sobrecargar interfaces
* Máximo 3 colores principales por vista
* Espaciado consistente (8px grid system)

---

## 7. ♿ Accesibilidad

* Contraste mínimo AA
* Navegación por teclado
* Labels accesibles (aria-label)
* Inputs con feedback visual

---

## 8. ⚙️ Convenciones Técnicas

### Frontend

* Framework recomendado: React / Vue
* CSS: Tailwind o CSS Modules

### Naming

* Componentes: PascalCase
* Variables: camelCase
* Archivos: kebab-case

---

## 9. 🤖 Instrucciones para IA (IMPORTANTE)

Cuando generes código:

### SIEMPRE:

* Usar color principal #39A900 en acciones principales
* Diseñar layout tipo dashboard administrativo
* Incluir:

  * Sidebar
  * Navbar
  * Tabla o formulario funcional
* Aplicar buenas prácticas UX

---

### EJEMPLO PROMPT

```
Crea una vista admin para gestionar ofertas educativas del SENA.

Requisitos:
- Tabla con columnas: programa, modalidad, estado, fecha
- Botón "Crear oferta" en verde #39A900
- Sidebar con navegación
- Diseño limpio, institucional y accesible
- Responsive
```

---

## 10. 📊 Estructura de Módulos

* Dashboard
* Gestión de ofertas
* Usuarios / aprendices
* Reportes
* Configuración

---

## 11. 🚫 Restricciones

* No modificar el logo SENA
* No cambiar color institucional
* No usar branding externo
* No usar estilos "gaming", neon, etc.

---

## 12. ✅ Buenas Prácticas

✔ Consistencia visual
✔ Componentización
✔ Reutilización de estilos
✔ Código limpio
✔ UI predecible

---

## 13. 📎 Notas Finales

Este documento sirve como contexto persistente para IA, asegurando que todo el código generado respete la identidad visual del SENA y los estándares de software moderno.

---

# 🔚 END OF FILE

[1]: https://www.sena.edu.co/es-co/Documents/MANUAL_IDENTIDAD_VISUAL_SENA_2024.pdf?utm_source=chatgpt.com "MANUAL D E ID ENT I DA D  V I S UA L"
