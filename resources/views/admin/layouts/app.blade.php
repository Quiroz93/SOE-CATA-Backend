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
        .topbar { background: #39A900; color: #fff; padding: 16px 24px; }
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
            <a href="{{ route('admin.centros.index') }}" class="{{ request()->routeIs('admin.centros.*') ? 'active' : '' }}">Centros</a>
            <a href="#" class="">Ofertas</a>
            <a href="#" class="">Usuarios</a>
            <a href="#" class="">Reportes</a>
            <a href="#" class="">Configuración</a>
        </nav>
    </aside>
    <div class="flex-1 flex flex-col">
        <header class="topbar flex justify-between items-center">
            <div class="font-semibold text-xl">Panel Administrativo SENA</div>
            <div>
                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                <a href="{{ route('profile.edit') }}" class="ml-4 underline">Perfil</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="ml-4 bg-white text-green-700 px-3 py-1 rounded">Salir</button>
                </form>
            </div>
        </header>
        <main class="content">
            @yield('content')
        </main>
    </div>
</body>
</html>
