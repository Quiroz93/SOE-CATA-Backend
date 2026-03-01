# Estructura CSS Modular - SENA CATA

## Descripción

Este documento describe la estructura modular del CSS implementada para reemplazar Tailwind CSS con CSS puro, manteniendo la modularidad y escalabilidad del sistema.

## Archivos Creados

### `/public/css/admin-layout.css`

Archivo CSS modular que contiene todos los estilos del layout administrativo.

## Estructura del CSS

El archivo está organizado en las siguientes secciones:

### 1. **BASE STYLES**
Estilos base para el body y elementos fundamentales:
- `.admin-body`: Tipografía, antialiasing y fondo general

### 2. **NAVIGATION**
Estilos para la barra de navegación:
- `.admin-nav`: Contenedor principal de navegación
- `.admin-nav__container`: Contenedor con max-width y padding responsivo
- `.admin-nav__content`: Flexbox para organizar elementos

### 3. **NAVIGATION - BRAND**
Estilos para la marca/logo:
- `.admin-nav__brand`: Contenedor del logo
- `.admin-nav__logo`: Enlace del logo
- `.admin-nav__logo-text--primary`: Texto "SENA" (verde)
- `.admin-nav__logo-text--secondary`: Texto "CATA" (gris)

### 4. **NAVIGATION - MENU**
Estilos para el menú de navegación:
- `.admin-nav__menu`: Menú oculto en móvil, visible en tablet+
- `.admin-nav__link`: Enlaces del menú con hover effects

### 5. **NAVIGATION - USER**
Estilos para la sección de usuario:
- `.admin-nav__user`: Contenedor de usuario
- `.admin-nav__username`: Nombre del usuario
- `.admin-nav__logout-form`: Formulario de logout
- `.admin-nav__logout-btn`: Botón de logout con hover effect

### 6. **MAIN CONTENT**
Estilos para el contenido principal:
- `.admin-main`: Contenedor principal con altura mínima

### 7. **UTILITY CLASSES**
Clases utilitarias reutilizables:
- `.flex`, `.items-center`, `.gap-*`

### 8. **RESPONSIVE UTILITIES**
Utilidades responsivas:
- `.hidden-mobile`: Oculto en móvil, visible en tablet+

## Metodología BEM

El CSS sigue la metodología BEM (Block Element Modifier):

```
.bloque__elemento--modificador
```

**Ejemplo:**
```css
.admin-nav              /* Bloque */
.admin-nav__logo        /* Elemento */
.admin-nav__logo--large /* Modificador */
```

## Puntos de Ruptura Responsivos

El sistema usa los siguientes breakpoints:

- **Mobile**: < 640px (por defecto)
- **Tablet**: ≥ 640px (`sm`)
- **Tablet Grande**: ≥ 768px (`md`)
- **Desktop**: ≥ 1024px (`lg`)

## Colores del Sistema

### Colores Principales
- **Verde SENA**: `#16a34a` (botones, enlaces activos, marca)
- **Rojo Logout**: `#dc2626` (botón de logout hover)

### Colores de Texto
- **Texto Principal**: `#374151` (gris oscuro)
- **Fondo Claro**: `#f3f4f6` (gris muy claro)

### Colores de UI
- **Blanco**: `#ffffff` (navegación, tarjetas)
- **Borde**: `#e5e7eb` (bordes sutiles)

## Cómo Extender el Sistema

### Agregar Nuevos Componentes

1. **Crear una nueva sección en el CSS:**
```css
/* ===========================
   NOMBRE DEL COMPONENTE
   =========================== */

.admin-componente {
    /* Estilos base */
}

.admin-componente__elemento {
    /* Estilos del elemento */
}
```

2. **Usar nomenclatura BEM consistente**

3. **Agregar media queries si es necesario**

### Agregar Nuevas Páginas

Para nuevas páginas del admin:

1. Crear un nuevo archivo CSS en `/public/css/`
2. Seguir la misma estructura modular
3. Incluir el archivo en el blade correspondiente:
```php
<link rel="stylesheet" href="{{ asset('css/nombre-componente.css') }}">
```

### Mantener Consistencia

- Usar los colores definidos en la paleta
- Seguir los breakpoints establecidos
- Mantener la metodología BEM
- Documentar cambios importantes

## Migración de Tailwind a CSS Puro

### Antes (Tailwind)
```html
<div class="flex justify-between items-center h-16">
```

### Después (CSS Puro)
```html
<div class="admin-nav__content">
```

```css
.admin-nav__content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 4rem;
}
```

## Ventajas de esta Implementación

1. **Sin dependencias**: No requiere Tailwind ni procesadores
2. **Modular**: Cada sección está claramente definida
3. **Mantenible**: Nomenclatura clara y consistente
4. **Escalable**: Fácil de extender con nuevos componentes
5. **Responsivo**: Media queries implementadas correctamente
6. **Performance**: CSS puro es más rápido que frameworks
7. **Control total**: Control completo sobre cada estilo

## Archivos Modificados

- `resources/views/admin/layouts/app.blade.php`: Actualizado para usar clases personalizadas
- `public/css/admin-layout.css`: Nuevo archivo CSS modular (CREADO)

## Próximos Pasos

Si necesitas convertir más vistas:

1. Identificar las vistas que usan Tailwind
2. Crear archivos CSS modulares específicos
3. Actualizar las clases en los archivos blade
4. Probar la responsividad en diferentes dispositivos

## Soporte

Para dudas o problemas con el CSS modular, revisa este documento y la estructura del archivo `admin-layout.css`.
