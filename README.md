📘 Ofertas Educativas – Sistema Web

Sistema web para la gestión y publicación de programas de formación, orientado a público general y administración interna.

Proyecto desarrollado como migración estructural a Laravel 12, aplicando buenas prácticas de arquitectura, control de versiones y diseño institucional alineado con el Manual de Identidad Visual SENA 2024.

🚀 Tecnologías Principales

Backend: Laravel 12

Frontend: Vue 3

Estilos: Tailwind CSS

Autenticación: Laravel Breeze

Roles y permisos: Spatie Laravel Permission

Base de datos: MySQL

Control de versiones: Git

Entorno de desarrollo: VS Code

📂 Arquitectura del Proyecto
app/
 ├── Models/
 ├── Http/
 │    ├── Controllers/
 │    ├── Requests/
 │    └── Middleware/
 ├── Services/
 └── Policies/

database/
 ├── migrations/
 └── seeders/

resources/
 ├── views/
 ├── js/
 └── css/

routes/
 ├── web.php
 └── api.php

Estructura diseñada bajo principios de:

Separación de responsabilidades MVC

Escalabilidad

Dominio organizado

Preparado para crecimiento modular

⚙️ Instalación del Proyecto
1️⃣ Clonar repositorio
git clone <url-del-repositorio>
cd ofertas-educativas
2️⃣ Instalar dependencias
composer install
npm install
3️⃣ Configurar entorno
cp .env.example .env
php artisan key:generate

Configurar variables de base de datos en .env.

4️⃣ Ejecutar migraciones
php artisan migrate

Si existen seeders:

php artisan db:seed
5️⃣ Ejecutar entorno local
npm run dev
php artisan serve
🔐 Sistema de Roles

Implementado con Spatie Permission.

Ejemplo de roles:

Administrador

Coordinador

Invitado

Usuario público

Permite:

Control granular por permisos

Middleware de protección por rol

Escalabilidad futura

🎨 Sistema de Diseño

Alineado con:

Manual de Identidad Visual SENA 2024

Paleta institucional

Tokens de diseño

Componentes reutilizables

UI consistente con Tailwind

Incluye:

Botones estandarizados

Formularios accesibles

Tarjetas informativas

Componentes Vue reutilizables

🔄 Flujo de Trabajo Git

Modelo recomendado:

main → producción
develop → integración
feature/* → nuevas funcionalidades
fix/* → correcciones

Convención de commits:

feat: nueva funcionalidad
fix: corrección de error
refactor: mejora estructural
docs: cambios en documentación
📊 Roadmap Técnico

 Base Laravel 12 limpia

 Instalación Breeze

 Integración Vue

 Implementación Tailwind

 Sistema completo de roles y permisos

 Módulo estadístico

 Dashboard administrativo

 Despliegue en hosting

🌐 Despliegue

Pasos generales:

Configurar servidor (Apache/Nginx)

Configurar .env producción

Ejecutar:

php artisan migrate --force
npm run build

Ajustar permisos de storage y bootstrap/cache

👨‍💻 Autor

Proyecto desarrollado como ejercicio de migración estructural y mejora arquitectónica.

Estudiante de Análisis y Desarrollo de Software.

📄 Licencia

Proyecto de uso académico y administrativo.