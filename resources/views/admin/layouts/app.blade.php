<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | SENA</title>
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
</head>
<body>
    <div class="admin-shell">
    <aside class="sidebar flex flex-col p-4">
        <div class="sidebar-brand">
            <img src="{{ asset('images/Logosimbolo-SENA.svg') }}" alt="SENA Logo" class="sidebar-logo">
            <span class="sidebar-title">SENA Admin</span>
        </div>
        <nav class="flex-1">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.centros.index') }}" class="{{ request()->routeIs('admin.centros.*') ? 'active' : '' }}">Centros</a>
            <a href="{{ route('admin.ofertas.index') }}" class="{{ request()->routeIs('admin.ofertas.*') ? 'active' : '' }}">Ofertas</a>
            <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">Usuarios</a>
            <a href="{{ route('admin.reportes.index') }}" class="{{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">Reportes</a>
            <a href="{{ route('admin.welcome') }}" class="{{ request()->routeIs('admin.welcome') ? 'active' : '' }}">Configuración</a>
        </nav>
    </aside>
    <div class="flex-1 flex flex-col">
        @include('admin.layouts.topbar')
        <main class="content">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
