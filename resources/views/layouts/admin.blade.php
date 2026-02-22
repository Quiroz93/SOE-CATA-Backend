<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Administración')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <div id="app">
        <!-- Navbar superior -->
        <nav class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="font-bold text-lg text-gray-800">Panel Admin</span>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Espacio para notificaciones -->
                <div id="admin-notifications"></div>
                <!-- Usuario / Logout -->
                <span class="text-gray-600">@auth {{ Auth::user()->name }} @endauth</span>
            </div>
        </nav>
        <div class="flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-white border-r border-gray-200 min-h-screen p-4 hidden md:block">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.programas.index') }}" class="block py-2 px-3 rounded hover:bg-gray-100 text-gray-700">Programas</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ofertas.index') }}" class="block py-2 px-3 rounded hover:bg-gray-100 text-gray-700">Ofertas</a>
                    </li>
                    {{-- Ejemplo real de integración de permisos: --}}
                    {{-- @can('viewAny', App\Models\Instructor::class)
                    <li>
                        <a href="{{ route('admin.instructores.index') }}" class="block py-2 px-3 rounded hover:bg-gray-100 text-gray-700">Instructores</a>
                    </li>
                    @endcan --}}
                </ul>
            </aside>
            <!-- Contenido principal -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>