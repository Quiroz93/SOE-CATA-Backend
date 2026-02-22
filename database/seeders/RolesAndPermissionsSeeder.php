<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 1. Resetear caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Crear permisos organizados por módulos
        $permissions = [
            // Programas
            'programas.view', 'programas.create', 'programas.update', 'programas.delete',
            // Ofertas
            'ofertas.view', 'ofertas.create', 'ofertas.update', 'ofertas.delete',
            // Inscripciones
            'inscripciones.view', 'inscripciones.create', 'inscripciones.update', 'inscripciones.delete',
            // Matrículas
            'matriculas.view', 'matriculas.create', 'matriculas.update', 'matriculas.delete',
            // Usuarios
            'usuarios.view', 'usuarios.create', 'usuarios.update', 'usuarios.delete',
            // Seguridad
            'seguridad.view', 'seguridad.manage',
            // Reportes
            'reportes.view', 'reportes.generate',
            // Contenido
            'contenido.view', 'contenido.create', 'contenido.update', 'contenido.delete',
            // Comunicación
            'comunicacion.view', 'comunicacion.send',
            // Sistema
            'sistema.view', 'sistema.manage',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 3. Crear roles
        $roles = [
            'Super Admin',
            'Seguridad y Auditoría',
            'Administración del Sistema',
            'Coordinador Académico',
            'Secretaría Académica',
            'Instructor',
            'Soporte Técnico',
            'Gestión de Contenido',
            'Reportes y Estadísticas',
            'Comunicación',
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 4. Asignar permisos a roles (ejemplo básico, personalizar según necesidades)
        Role::findByName('Seguridad y Auditoría')->givePermissionTo([
            'seguridad.view', 'seguridad.manage', 'usuarios.view', 'usuarios.update', 'reportes.view', 'reportes.generate',
        ]);
        Role::findByName('Administración del Sistema')->givePermissionTo($permissions);
        Role::findByName('Coordinador Académico')->givePermissionTo([
            'programas.view', 'programas.create', 'programas.update', 'ofertas.view', 'ofertas.create', 'ofertas.update',
            'inscripciones.view', 'inscripciones.create', 'matriculas.view', 'matriculas.create',
            'reportes.view', 'reportes.generate',
        ]);
        Role::findByName('Secretaría Académica')->givePermissionTo([
            'inscripciones.view', 'inscripciones.create', 'matriculas.view', 'matriculas.create',
        ]);
        Role::findByName('Instructor')->givePermissionTo([
            'programas.view', 'ofertas.view', 'inscripciones.view', 'matriculas.view',
        ]);
        Role::findByName('Soporte Técnico')->givePermissionTo([
            'sistema.view', 'sistema.manage', 'usuarios.view', 'usuarios.update',
        ]);
        Role::findByName('Gestión de Contenido')->givePermissionTo([
            'contenido.view', 'contenido.create', 'contenido.update', 'contenido.delete',
        ]);
        Role::findByName('Reportes y Estadísticas')->givePermissionTo([
            'reportes.view', 'reportes.generate',
        ]);
        Role::findByName('Comunicación')->givePermissionTo([
            'comunicacion.view', 'comunicacion.send',
        ]);
        // Super Admin no requiere asignación manual de permisos (bypass por Gate::before)
    }
}
