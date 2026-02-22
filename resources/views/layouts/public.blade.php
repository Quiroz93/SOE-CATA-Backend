<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Público')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen flex flex-col">
    <div id="app">
        <!-- Header público -->
        <header class="bg-gray-900 text-white py-4 shadow">
            <div class="container mx-auto flex justify-between items-center px-4">
                <span class="font-bold text-xl">Ofertas Educativas SENA</span>
                <!-- Navegación básica -->
                <nav>
                    <ul class="flex space-x-6">
                        <li><a href="/" class="hover:underline">Inicio</a></li>
                        <li><a href="{{ route('public.ofertas.index') }}" class="hover:underline">Ofertas</a></li>
                        <li><a href="{{ route('public.programas.index') }}" class="hover:underline">Programas</a></li>
                        <li><a href="#" class="hover:underline">Contacto</a></li>
                        
                    </ul>
                </nav>
            </div>
        </header>
        <!-- Contenedor principal -->
        <main class="flex-1 container mx-auto px-4 py-8">
            @yield('content')
        </main>
        <!-- Footer público -->
        <footer class="bg-gray-900 text-white py-4 mt-8">
            <div class="container mx-auto text-center text-sm">
                &copy; {{ now()->year }} SENA. Todos los derechos reservados.
            </div>
        </footer>
    </div>
</body>
</html>