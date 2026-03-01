<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Público')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicons/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/public-layout.css') }}">
    @vite(['resources/js/app.js'])
</head>
<body class="public-body">
    <div id="app" class="public-app">
        <!-- Header público -->
        <header class="public-header">
            <div class="public-header__container">
                <span class="public-header__title">Ofertas Educativas SENA</span>
                <!-- Navegación básica -->
                <nav class="public-nav">
                    <ul class="public-nav__list">
                        <li class="public-nav__item">
                            <a href="/" class="public-nav__link {{ request()->is('/') ? 'public-nav__link--active' : '' }}">
                                Inicio
                            </a>
                        </li>
                        <li class="public-nav__item">
                            <a href="{{ route('public.ofertas.index') }}" class="public-nav__link {{ request()->routeIs('public.ofertas.*') ? 'public-nav__link--active' : '' }}">
                                Ofertas
                            </a>
                        </li>
                        <li class="public-nav__item">
                            <a href="{{ route('public.programas.index') }}" class="public-nav__link {{ request()->routeIs('public.programas.*') ? 'public-nav__link--active' : '' }}">
                                Programas
                            </a>
                        </li>
                        <li class="public-nav__item">
                            <a href="#" class="public-nav__link">
                                Contacto
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
        <!-- Contenedor principal -->
        <main class="public-main">
            @yield('content')
        </main>
        <!-- Footer público -->
        <footer class="public-footer">
            <div class="public-footer__container">
                <p class="public-footer__text">
                    &copy; {{ now()->year }} SENA. Todos los derechos reservados.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>