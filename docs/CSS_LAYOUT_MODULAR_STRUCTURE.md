# Estructura Modular de CSS - Admin Layout

## Organización por Responsabilidades

El archivo `admin-layout.css` ha sido reorganizado en módulos independientes según su responsabilidad específica. Cada archivo maneja un aspecto único del layout administrativo.

## Estructura de Archivos

```
resources/css/
├── admin.css                          # Punto de entrada principal
├── admin-layout.css                   # Orquestador de módulos de layout
├── navigation-dropdowns.css           # Dropdowns de navegación (menús desplegables)
├── admin-crud.css                     # Tablas CRUD
├── dashboard.css                      # Dashboard específicos
└── layout/                            # 📁 Módulos de layout organizados
    ├── admin-base.css                 # Estilos base del body
    ├── admin-navigation.css           # Estructura principal de navegación
    ├── admin-navigation-brand.css     # Logo y marca
    ├── admin-navigation-menu.css      # Menú de links desktop
    ├── admin-navigation-user.css      # Dropdown de usuario
    ├── admin-navigation-mobile.css    # Todo mobile: hamburger, menú, usuario
    ├── admin-main-content.css         # Contenedor principal de contenido
    └── admin-utilities.css            # Clases de utilidad y responsive
```

## Descripción de Módulos

### 📄 `layout/admin-base.css`
**Responsabilidad:** Estilos base del cuerpo de la aplicación
- `.admin-body` con tipografía, antialiasing y color de fondo

### 📄 `layout/admin-navigation.css`
**Responsabilidad:** Estructura y contenedores de la barra de navegación
- `.admin-nav` - Contenedor principal
- `.admin-nav__container` - Wrapper con max-width
- `.admin-nav__content` - Layout flex principal
- `.admin-nav__left` - Sección izquierda

### 📄 `layout/admin-navigation-brand.css`
**Responsabilidad:** Logo y marca de la aplicación
- `.admin-nav__brand` - Contenedor del logo
- `.admin-nav__logo` - Link del logo
- `.admin-nav__logo-img` - Imagen del logo
- `.admin-nav__logo-text--primary` - Texto SENA (verde)
- `.admin-nav__logo-text--secondary` - Texto CATA (gris)

### 📄 `layout/admin-navigation-menu.css`
**Responsabilidad:** Links del menú de navegación desktop
- `.admin-nav__menu` - Contenedor de links
- `.admin-nav__link` - Links individuales
- `.admin-nav__link--active` - Estado activo
- Estados hover y transiciones

### 📄 `layout/admin-navigation-user.css`
**Responsabilidad:** Dropdown de usuario (desktop)
- `.admin-nav__user-section` - Sección de usuario
- `.admin-nav__dropdown` - Contenedor dropdown
- `.admin-nav__dropdown-trigger` - Botón trigger
- `.admin-nav__user-avatar` - Avatar de usuario
- `.admin-nav__dropdown-content` - Contenido del dropdown
- `.admin-nav__dropdown-link` - Links dentro del dropdown

### 📄 `layout/admin-navigation-mobile.css`
**Responsabilidad:** Todo lo relacionado con navegación móvil
- **Hamburger:** `.admin-nav__hamburger`, `.admin-nav__hamburger-btn`, iconos
- **Mobile Menu:** `.admin-nav__mobile`, `.admin-nav__mobile-links`, `.admin-nav__mobile-link`
- **Mobile User:** `.admin-nav__mobile-user`, avatar, nombre, email, links

### 📄 `layout/admin-main-content.css`
**Responsabilidad:** Área principal de contenido
- `.admin-main` con padding responsive

### 📄 `layout/admin-utilities.css`
**Responsabilidad:** Clases de utilidad general
- `.flex`, `.items-center`, `.gap-*`
- `.hidden-mobile` y otras utilidades responsive

### 📄 `navigation-dropdowns.css`
**Responsabilidad:** Menús desplegables de navegación (fuera de layout/)
- Desktop dropdowns: `.admin-nav__dropdown-menu`, `.admin-nav__submenu`
- Mobile dropdowns: `.admin-nav__mobile-dropdown-menu`, `.admin-nav__mobile-submenu`
- Flechas animadas y estados hover/active

## Flujo de Importación

```css
admin.css
  ├── admin-layout.css (orquestador)
  │   ├── layout/admin-base.css
  │   ├── layout/admin-navigation.css
  │   ├── layout/admin-navigation-brand.css
  │   ├── layout/admin-navigation-menu.css
  │   ├── layout/admin-navigation-user.css
  │   ├── layout/admin-navigation-mobile.css
  │   ├── layout/admin-main-content.css
  │   └── layout/admin-utilities.css
  ├── navigation-dropdowns.css
  ├── admin-crud.css
  └── dashboard.css
```

## Ventajas de esta Organización

✅ **Separación de Responsabilidades:** Cada archivo tiene un propósito claro y único
✅ **Mantenibilidad:** Fácil localizar y modificar estilos específicos
✅ **Escalabilidad:** Agregar nuevos módulos sin afectar existentes
✅ **Reutilización:** Módulos independientes pueden usarse en otros contextos
✅ **Debugging:** Problemas aislados a módulos específicos
✅ **Colaboración:** Múltiples desarrolladores pueden trabajar sin conflictos

## Convenciones de Nomenclatura

- **Prefijo:** Todos los archivos inician con `admin-`
- **Carpeta:** Los módulos de layout están en `layout/`
- **Descriptivos:** Nombres claros que indican responsabilidad
- **BEM:** Clases CSS siguen metodología BEM (Block__Element--Modifier)

## Cómo Agregar Nuevos Módulos

1. Crear archivo en `resources/css/layout/admin-[nombre].css`
2. Agregar importación en `admin-layout.css`
3. Mantener responsabilidad única
4. Documentar propósito en comentario de cabecera
5. Seguir convenciones BEM existentes

## Ejemplo: Agregar Módulo de Breadcrumbs

```css
/* 1. Crear: layout/admin-breadcrumbs.css */
/**
 * SENA CATA - Admin Breadcrumbs
 * Navegación de migas de pan
 */
.admin-breadcrumbs { /* estilos */ }
```

```css
/* 2. Importar en admin-layout.css */
@import './layout/admin-breadcrumbs.css';
```

## Build y Despliegue

El sistema Vite procesa todos los imports y genera un único archivo compilado:
- **Desarrollo:** CSS con source maps para debugging
- **Producción:** CSS minificado y optimizado (admin-[hash].css)

---

**Fecha de Reorganización:** Marzo 4, 2026
**Autor:** Sistema de Migración SENA CATA
**Versión:** 2.0 - Arquitectura Modular
