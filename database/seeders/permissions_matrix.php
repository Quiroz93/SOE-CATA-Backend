<?php
// Seeder de permisos estructurados por módulo
return [
    // 1. Gestión de usuarios
    // Permite administrar usuarios del sistema
    'user.view',
    'user.view_any',
    'user.create',
    'user.update',
    'user.delete',
    'user.restore',
    'user.force_delete',
    'user.export',
    'user.manage',

    // 2. Gestión de roles
    // Permite administrar roles y asignaciones
    'role.view',
    'role.view_any',
    'role.create',
    'role.update',
    'role.delete',
    'role.restore',
    'role.force_delete',
    'role.manage',

    // 3. Gestión de ofertas
    'oferta.view',
    'oferta.view_any',
    'oferta.create',
    'oferta.update',
    'oferta.delete',
    'oferta.restore',
    'oferta.force_delete',
    'oferta.export',
    'oferta.publish',
    'oferta.close',
    'oferta.manage',

    // 4. Gestión de programas
    'programa.view',
    'programa.view_any',
    'programa.create',
    'programa.update',
    'programa.delete',
    'programa.restore',
    'programa.force_delete',
    'programa.export',
    'programa.publish',
    'programa.close',
    'programa.manage',

    // 5. Gestión de preinscritos
    'preinscrito.view',
    'preinscrito.view_any',
    'preinscrito.create',
    'preinscrito.update',
    'preinscrito.delete',
    'preinscrito.restore',
    'preinscrito.force_delete',
    'preinscrito.export',
    'preinscrito.approve',
    'preinscrito.reject',
    'preinscrito.manage',

    // 6. Gestión de inscritos
    'inscrito.view',
    'inscrito.view_any',
    'inscrito.create',
    'inscrito.update',
    'inscrito.delete',
    'inscrito.restore',
    'inscrito.force_delete',
    'inscrito.export',
    'inscrito.manage',

    // 7. Gestión de consolidaciones
    'consolidacion.view',
    'consolidacion.view_any',
    'consolidacion.create',
    'consolidacion.update',
    'consolidacion.delete',
    'consolidacion.restore',
    'consolidacion.force_delete',
    'consolidacion.export',
    'consolidacion.migrate',
    'consolidacion.manage',

    // 8. Gestión de novedades
    'novedad.view',
    'novedad.view_any',
    'novedad.create',
    'novedad.update',
    'novedad.delete',
    'novedad.restore',
    'novedad.force_delete',
    'novedad.export',
    'novedad.manage',

    // 9. Gestión de exportaciones
    'exportacion.view',
    'exportacion.view_any',
    'exportacion.create',
    'exportacion.update',
    'exportacion.delete',
    'exportacion.restore',
    'exportacion.force_delete',
    'exportacion.export',
    'exportacion.manage',

    // 10. Gestión de backups
    'backup.view',
    'backup.view_any',
    'backup.create',
    'backup.update',
    'backup.delete',
    'backup.restore',
    'backup.force_delete',
    'backup.export',
    'backup.backup',
    'backup.manage',

    // 11. Gestión de configuración del sistema
    'system.view',
    'system.update',
    'system.manage',

    // 12. Gestión de contenido público
    'contenido.view',
    'contenido.view_any',
    'contenido.create',
    'contenido.update',
    'contenido.delete',
    'contenido.restore',
    'contenido.force_delete',
    'contenido.publish',
    'contenido.manage',

    // 13. Gestión de instructores
    'instructor.view',
    'instructor.view_any',
    'instructor.create',
    'instructor.update',
    'instructor.delete',
    'instructor.restore',
    'instructor.force_delete',
    'instructor.export',
    'instructor.manage',

    // 14. Gestión de centros
    'centro.view',
    'centro.view_any',
    'centro.create',
    'centro.update',
    'centro.delete',
    'centro.restore',
    'centro.force_delete',
    'centro.export',
    'centro.manage',

    // 15. Módulo estadístico
    'estadistica.view',
    'estadistica.export',
    'estadistica.manage',
];
