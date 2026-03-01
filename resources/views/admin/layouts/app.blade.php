<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SENA | CATA - @yield('title', 'Admin')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicons/favicon.ico') }}">

    <!-- Styles -->
     @yield('styles')
    @vite(['resources/css/app.css'])
    
</head>
<body class="font-sans antialiased bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                        <div class="text-green-600 font-bold text-xl">SENA</div>
                        <span class="text-gray-700 font-semibold">CATA</span>
                    </a>
                </div>

                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-green-600 font-medium">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('admin.centros.index') }}" class="text-gray-700 hover:text-green-600 font-medium">
                        {{ __('Centros') }}
                    </a>
                    <a href="{{ route('admin.preinscritos.index') }}" class="text-gray-700 hover:text-green-600 font-medium">
                        {{ __('Preinscritos') }}
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 text-sm">{{ Auth::user()?->name ?? 'Usuario' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-red-600 font-medium text-sm">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen">
        @yield('content')
    </div>

    <!-- Scripts -->
    @vite(['resources/js/admin/dashboard.js'])
    @yield('scripts')
</body>
</html>