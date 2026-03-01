<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | SENA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', 'Roboto', 'Open Sans', sans-serif; }
        .sidebar { background: #00304D; color: #fff; min-width: 220px; }
        .sidebar a { color: #fff; display: block; padding: 12px 20px; border-radius: 6px; margin-bottom: 4px; }
        .sidebar a.active, .sidebar a:hover { background: #39A900; color: #fff; }
        .topbar { background: #39A900; color: #fff; padding: 12px 16px; display:flex; justify-content:space-between; align-items:center; gap:16px; }
        .topbar-brand { display:flex; align-items:center; gap:12px; }
        .topbar-logo { width:36px; height:36px; object-fit:contain; background:#fff; border-radius:8px; padding:4px; }
        .topbar-title { font-size:1rem; font-weight:600; }
        .topbar-actions { display:flex; align-items:center; gap:12px; }
        .profile-menu { position:relative; }
        .profile-menu summary { list-style:none; cursor:pointer; display:flex; align-items:center; gap:8px; padding:8px 12px; border-radius:8px; background:#007832; color:#fff; }
        .profile-menu summary::-webkit-details-marker { display:none; }
        .profile-name { font-size:0.9rem; font-weight:600; }
        .profile-dropdown { position:absolute; right:0; top:calc(100% + 8px); background:#fff; border-radius:10px; box-shadow:0 8px 20px rgba(0,0,0,0.12); min-width:220px; overflow:hidden; z-index:10; }
        .profile-dropdown a { display:block; text-decoration:none; color:#00304D; padding:10px 14px; font-size:0.9rem; }
        .profile-dropdown a:hover { background:#F8FAFC; }
        .logout-btn { border:none; background:#00304D; color:#fff; border-radius:8px; padding:8px 12px; font-size:0.9rem; cursor:pointer; }
        .logout-btn:hover { background:#001f33; }
        .content { padding: 32px; background: #F8FAFC; min-height: 100vh; }
    </style>
</head>
<body class="flex">
    <aside class="sidebar flex flex-col h-screen p-4">
        <div class="mb-8 flex items-center gap-2">
            <img src="/images/Logosimbolo-SENA.svg" alt="SENA Logo" class="h-8">
            <span class="font-bold text-lg">SENA Admin</span>
        </div>
        <nav class="flex-1">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.centros.index') }}" class="{{ request()->routeIs('admin.centros.*') ? 'active' : '' }}">Centros</a>
            <a href="{{ route('admin.ofertas.index') }}" class="{{ request()->routeIs('admin.ofertas.*') ? 'active' : '' }}">Ofertas</a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Usuarios</a>
            <a href="#" class="">Reportes</a>
            <a href="{{ route('admin.welcome') }}" class="{{ request()->routeIs('admin.welcome') ? 'active' : '' }}">Configuración</a>
        </nav>
    </aside>
    <div class="flex-1 flex flex-col">
        @include('admin.layouts.topbar')
        <main class="content">
            @yield('content')
        </main>
    </div>
</body>
</html>
