<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SENA | CATA - @yield('title', 'Admin')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicons/favicon.ico') }}">

    <!-- Styles importados via Vite - Módulo Admin -->
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    @yield('styles')

</head>

<body class="admin-body">
    <!-- Navigation -->
    <nav x-data="{ 
        open: false, 
        dropdownOpen: false,
        openMenus: {}
    }" @click.away="dropdownOpen = false" class="admin-nav">
        <div class="admin-nav__container">
            <div class="admin-nav__content">
                <!-- Left Side: Logo & Menu -->
                <div class="admin-nav__left">
                    <!-- Logo -->
                    <div class="admin-nav__brand">
                        <a href="{{ route('admin.dashboard') }}" class="admin-nav__logo">
                            <img src="{{ asset('images/Logosimbolo-SENA.svg') }}"
                                alt="SENA Logo"
                                class="admin-nav__logo-img">
                            <span class="admin-nav__logo-text--secondary">CATA</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation Links -->
                    <div class="admin-nav__menu">
                        <a href="{{ route('admin.dashboard') }}"
                            class="admin-nav__link {{ request()->routeIs('admin.dashboard') ? 'admin-nav__link--active' : '' }}">
                            {{ __('Dashboard') }}
                        </a>

                        <!-- Gestión de Oferta -->
                        <div class="admin-nav__dropdown-menu" @click.away="openMenus['oferta'] = false">
                            <button @click="openMenus['oferta'] = !openMenus['oferta']"
                                class="admin-nav__link admin-nav__link--with-dropdown {{ request()->routeIs(['admin.centros.*', 'admin.programas.*', 'admin.ofertas.*']) ? 'admin-nav__link--active' : '' }}">
                                {{ __('Gestión de Oferta') }}
                                <svg class="admin-nav__dropdown-arrow" :class="{ 'rotate': openMenus['oferta'] }" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="admin-nav__submenu" x-show="openMenus['oferta']">
                                <a href="{{ route('admin.centros.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.centros.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Centros') }}
                                </a>
                                <a href="{{ route('admin.programas.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.programas.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Programas') }}
                                </a>
                                <a href="{{ route('admin.ofertas.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.ofertas.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Ofertas') }}
                                </a>
                                <!-- Contenido -->
                                <a href="{{ route('admin.noticias.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.noticias.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Noticias') }}
                                </a>
                            </div>
                        </div>

                        <!-- Gestión de Preinscritos -->
                        <div class="admin-nav__dropdown-menu" @click.away="openMenus['preinscritos'] = false">
                            <button @click="openMenus['preinscritos'] = !openMenus['preinscritos']"
                                class="admin-nav__link admin-nav__link--with-dropdown {{ request()->routeIs(['admin.preinscritos.*', 'admin.inscritos.*', 'admin.novedades.*']) ? 'admin-nav__link--active' : '' }}">
                                {{ __('Gestión de Preinscritos') }}
                                <svg class="admin-nav__dropdown-arrow" :class="{ 'rotate': openMenus['preinscritos'] }" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="admin-nav__submenu" x-show="openMenus['preinscritos']">
                                <a href="{{ route('admin.preinscritos.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.preinscritos.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Preinscritos') }}
                                </a>
                                <a href="{{ route('admin.inscritos.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.inscritos.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Inscritos') }}
                                </a>
                                <a href="{{ route('admin.novedades.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.novedades.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Novedades') }}
                                </a>
                            </div>
                        </div>

                        <!-- Gestión Académica -->
                        <div class="admin-nav__dropdown-menu" @click.away="openMenus['academica'] = false">
                            <button @click="openMenus['academica'] = !openMenus['academica']"
                                class="admin-nav__link admin-nav__link--with-dropdown {{ request()->routeIs(['admin.instructores.*', 'admin.competencias.*']) ? 'admin-nav__link--active' : '' }}">
                                {{ __('Gestión Académica') }}
                                <svg class="admin-nav__dropdown-arrow" :class="{ 'rotate': openMenus['academica'] }" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="admin-nav__submenu" x-show="openMenus['academica']">
                                <a href="{{ route('admin.instructores.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.instructores.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Instructores') }}
                                </a>
                                <a href="{{ route('admin.competencias.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.competencias.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Competencias') }}
                                </a>
                            </div>
                        </div>

                        <!-- Administración -->
                        <div class="admin-nav__dropdown-menu" @click.away="openMenus['admin'] = false">
                            <button @click="openMenus['admin'] = !openMenus['admin']"
                                class="admin-nav__link admin-nav__link--with-dropdown {{ request()->routeIs(['admin.usuarios.*', 'admin.tipo-novedad.*']) ? 'admin-nav__link--active' : '' }}">
                                {{ __('Administración') }}
                                <svg class="admin-nav__dropdown-arrow" :class="{ 'rotate': openMenus['admin'] }" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="admin-nav__submenu" x-show="openMenus['admin']">
                                <a href="{{ route('admin.usuarios.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.usuarios.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Usuarios') }}
                                </a>
                                <a href="{{ route('admin.tipo-novedad.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.tipo-novedad.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Tipos de Novedad') }}
                                </a>
                                <a href="{{ route('admin.reportes.index') }}"
                                    class="admin-nav__submenu-link {{ request()->routeIs('admin.reportes.*') ? 'admin-nav__submenu-link--active' : '' }}">
                                    {{ __('Reportes') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: User Dropdown & Hamburger -->
                <div class="admin-nav__user-section">
                    <!-- User Dropdown (Desktop) -->
                    <div class="admin-nav__dropdown" :class="{ 'is-open': dropdownOpen }">
                        <button @click="dropdownOpen = !dropdownOpen" class="admin-nav__dropdown-trigger">
                            @if(Auth::user()?->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                alt="{{ Auth::user()->name }}"
                                class="admin-nav__user-avatar">
                            @else
                            <div class="admin-nav__user-avatar-placeholder">
                                <svg fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                            </div>
                            @endif
                            <div>{{ Auth::user()?->name ?? 'Usuario' }}</div>
                            <svg class="admin-nav__dropdown-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="admin-nav__dropdown-content">
                            <a href="{{ route('profile.edit') }}" class="admin-nav__dropdown-link">
                                {{ __('Profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    class="admin-nav__dropdown-link"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Hamburger Button (Mobile) -->
                <div class="admin-nav__hamburger">
                    <button @click="open = !open" class="admin-nav__hamburger-btn" :class="{ 'menu-open': open }">
                        <svg class="admin-nav__hamburger-icon" viewBox="0 0 24 24">
                            <path class="admin-nav__hamburger-icon-open"
                                :class="{ 'hidden': open }"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path class="admin-nav__hamburger-icon-close"
                                :class="{ 'hidden': !open }"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="admin-nav__mobile" :class="{ 'is-open': open }">
            <!-- Mobile Navigation Links -->
            <div class="admin-nav__mobile-links">
                <a href="{{ route('admin.dashboard') }}"
                    class="admin-nav__mobile-link {{ request()->routeIs('admin.dashboard') ? 'admin-nav__mobile-link--active' : '' }}">
                    {{ __('Dashboard') }}
                </a>

                <!-- Gestión de Oferta (Mobile) -->
                <div class="admin-nav__mobile-dropdown-menu">
                    <button @click="openMenus['mobile-oferta'] = !openMenus['mobile-oferta']"
                        class="admin-nav__mobile-link admin-nav__mobile-link--with-dropdown {{ request()->routeIs(['admin.centros.*', 'admin.programas.*', 'admin.ofertas.*']) ? 'admin-nav__mobile-link--active' : '' }}">
                        {{ __('Gestión de Oferta') }}
                        <svg class="admin-nav__mobile-dropdown-arrow" :class="{ 'rotate': openMenus['mobile-oferta'] }" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="admin-nav__mobile-submenu" x-show="openMenus['mobile-oferta']">
                        <a href="{{ route('admin.centros.index') }}"
                            class="admin-nav__mobile-submenu-link {{ request()->routeIs('admin.centros.*') ? 'admin-nav__mobile-submenu-link--active' : '' }}">
                            {{ __('Centros') }}
                        </a>
                        <a href="{{ route('admin.programas.index') }}"
                            class="admin-nav__mobile-submenu-link {{ request()->routeIs('admin.programas.*') ? 'admin-nav__mobile-submenu-link--active' : '' }}">
                            {{ __('Programas') }}
                        </a>
                        <a href="{{ route('admin.ofertas.index') }}"
                            class="admin-nav__mobile-submenu-link {{ request()->routeIs('admin.ofertas.*') ? 'admin-nav__mobile-submenu-link--active' : '' }}">
                            {{ __('Ofertas') }}
                        </a>
                    </div>
                </div>

                <!-- Gestión de Preinscritos (Mobile) -->
                <div class="admin-nav__mobile-dropdown-menu">
                    <button @click="openMenus['mobile-preinscritos'] = !openMenus['mobile-preinscritos']"
                        class="admin-nav__mobile-link admin-nav__mobile-link--with-dropdown {{ request()->routeIs(['admin.preinscritos.*', 'admin.inscritos.*', 'admin.novedades.*']) ? 'admin-nav__mobile-link--active' : '' }}">
                        {{ __('Gestión de Preinscritos') }}
                        <svg class="admin-nav__mobile-dropdown-arrow" :class="{ 'rotate': openMenus['mobile-preinscritos'] }" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="admin-nav__mobile-submenu" x-show="openMenus['mobile-preinscritos']">
                        <a href="{{ route('admin.preinscritos.index') }}"
                            class="admin-nav__mobile-submenu-link {{ request()->routeIs('admin.preinscritos.*') ? 'admin-nav__mobile-submenu-link--active' : '' }}">
                            {{ __('Preinscritos') }}
                        </a>
                        <a href="{{ route('admin.inscritos.index') }}"
                            class="admin-nav__mobile-submenu-link {{ request()->routeIs('admin.inscritos.*') ? 'admin-nav__mobile-submenu-link--active' : '' }}">
                            {{ __('Inscritos') }}
                        </a>
                        <a href="{{ route('admin.novedades.index') }}"
                            class="admin-nav__mobile-submenu-link {{ request()->routeIs('admin.novedades.*') ? 'admin-nav__mobile-submenu-link--active' : '' }}">
                            {{ __('Novedades') }}
                        </a>
                    </div>
                </div>

                <!-- Gestión Académica (Mobile) -->
                <div class="admin-nav__mobile-dropdown-menu">
                    <button @click="openMenus['mobile-academica'] = !openMenus['mobile-academica']"
                        class="admin-nav__mobile-link admin-nav__mobile-link--with-dropdown {{ request()->routeIs(['admin.instructores.*', 'admin.competencias.*']) ? 'admin-nav__mobile-link--active' : '' }}">
                        {{ __('Gestión Académica') }}
                        <svg class="admin-nav__mobile-dropdown-arrow" :class="{ 'rotate': openMenus['mobile-academica'] }" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="admin-nav__mobile-submenu" x-show="openMenus['mobile-academica']">
                        <a href="{{ route('admin.instructores.index') }}"
                            class="admin-nav__mobile-submenu-link {{ request()->routeIs('admin.instructores.*') ? 'admin-nav__mobile-submenu-link--active' : '' }}">
                            {{ __('Instructores') }}
                        </a>
                        <a href="{{ route('admin.competencias.index') }}"
                            class="admin-nav__mobile-submenu-link {{ request()->routeIs('admin.competencias.*') ? 'admin-nav__mobile-submenu-link--active' : '' }}">
                            {{ __('Competencias') }}
                        </a>
                    </div>
                </div>

                <!-- Contenido (Mobile) -->
                <a href="{{ route('admin.noticias.index') }}"
                    class="admin-nav__mobile-link {{ request()->routeIs('admin.noticias.*') ? 'admin-nav__mobile-link--active' : '' }}">
                    {{ __('Noticias') }}
                </a>

                <!-- Administración (Mobile) -->
                <div class="admin-nav__mobile-dropdown-menu">
                    <button @click="openMenus['mobile-admin'] = !openMenus['mobile-admin']"
                        class="admin-nav__mobile-link admin-nav__mobile-link--with-dropdown {{ request()->routeIs(['admin.usuarios.*', 'admin.tipo-novedad.*']) ? 'admin-nav__mobile-link--active' : '' }}">
                        {{ __('Administración') }}
                        <svg class="admin-nav__mobile-dropdown-arrow" :class="{ 'rotate': openMenus['mobile-admin'] }" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="admin-nav__mobile-submenu" x-show="openMenus['mobile-admin']">
                        <a href="{{ route('admin.usuarios.index') }}"
                            class="admin-nav__mobile-submenu-link {{ request()->routeIs('admin.usuarios.*') ? 'admin-nav__mobile-submenu-link--active' : '' }}">
                            {{ __('Usuarios') }}
                        </a>
                        <a href="{{ route('admin.tipo-novedad.index') }}"
                            class="admin-nav__mobile-submenu-link {{ request()->routeIs('admin.tipo-novedad.*') ? 'admin-nav__mobile-submenu-link--active' : '' }}">
                            {{ __('Tipos de Novedad') }}
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.reportes.index') }}"
                    class="admin-nav__mobile-link {{ request()->routeIs('admin.reportes.*') ? 'admin-nav__mobile-link--active' : '' }}">
                    {{ __('Reportes') }}
                </a>
            </div>

            <!-- Mobile User Options -->
            <div class="admin-nav__mobile-user">
                <div class="admin-nav__mobile-user-info">
                    @if(Auth::user()?->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                        alt="{{ Auth::user()->name }}"
                        class="admin-nav__mobile-user-avatar">
                    @else
                    <div class="admin-nav__mobile-user-avatar-placeholder">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </div>
                    @endif
                    <div>
                        <div class="admin-nav__mobile-user-name">{{ Auth::user()?->name ?? 'Usuario' }}</div>
                        <div class="admin-nav__mobile-user-email">{{ Auth::user()?->email ?? '' }}</div>
                    </div>
                </div>

                <div class="admin-nav__mobile-user-links">
                    <a href="{{ route('profile.edit') }}" class="admin-nav__mobile-link">
                        {{ __('Profile') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                            class="admin-nav__mobile-link"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="admin-main">
        @yield('content')
    </div>

    <!-- Scripts -->
    @yield('scripts')
</body>

</html>