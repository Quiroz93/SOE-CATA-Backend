<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | SENA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin: 0; font-family: 'Inter', 'Roboto', 'Open Sans', sans-serif; }
        .admin-shell { display: flex; min-height: 100vh; }
        .sidebar { background: #00304D; color: #fff; width: 220px; flex-shrink: 0; min-height: 100vh; }
        .sidebar-brand { margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem; }
        .sidebar-logo { width: 24px; height: 24px; object-fit: contain; display: block; }
        .sidebar-title { font-size: 1.05rem; line-height: 1.2; font-weight: 700; white-space: nowrap; }
        .sidebar a { color: #fff; display: block; padding: 12px 20px; border-radius: 6px; margin-bottom: 4px; text-decoration: none; font-size: 0.95rem; }
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
        .content { padding: 32px; background: #F8FAFC; min-height: calc(100vh - 60px); }

        @media (max-width: 1024px) {
            .sidebar { width: 200px; }
            .topbar-title { font-size: 0.95rem; }
            .profile-name { max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        }

        @media (max-width: 900px) {
            .admin-shell { flex-direction: column; }
            .sidebar { width: 100%; min-height: auto; }
            .sidebar-brand { margin-bottom: 1rem; }
            .sidebar nav { display: flex; flex-wrap: wrap; gap: 8px; }
            .sidebar nav a { margin-bottom: 0; padding: 10px 14px; }
            .topbar { flex-direction: column; align-items: flex-start; }
            .topbar-actions { width: 100%; justify-content: space-between; }
            .profile-dropdown { right: auto; left: 0; }
            .content { padding: 20px; min-height: auto; }
        }

        @media (max-width: 520px) {
            .topbar-logo { width: 30px; height: 30px; }
            .topbar-title { font-size: 0.9rem; }
            .logout-btn { padding: 7px 10px; font-size: 0.85rem; }
            .profile-menu summary { padding: 7px 10px; }
            .sidebar-title { font-size: 0.95rem; }
        }
    </style>
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
