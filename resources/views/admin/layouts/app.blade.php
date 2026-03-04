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
    <nav x-data="{ open: false, dropdownOpen: false }" @click.away="dropdownOpen = false" class="admin-nav">
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
                        <a href="{{ route('admin.centros.index') }}" 
                           class="admin-nav__link {{ request()->routeIs('admin.centros.*') ? 'admin-nav__link--active' : '' }}">
                            {{ __('Centros') }}
                        </a>
                        <a href="{{ route('admin.programas.index') }}" 
                           class="admin-nav__link {{ request()->routeIs('admin.programas.*') ? 'admin-nav__link--active' : '' }}">
                            {{ __('Programas') }}
                        </a>
                        <a href="{{ route('admin.ofertas.index') }}" 
                           class="admin-nav__link {{ request()->routeIs('admin.ofertas.*') ? 'admin-nav__link--active' : '' }}">
                            {{ __('Ofertas') }}
                        </a>
                        <a href="{{ route('admin.preinscritos.index') }}" 
                           class="admin-nav__link {{ request()->routeIs('admin.preinscritos.*') ? 'admin-nav__link--active' : '' }}">
                            {{ __('Preinscritos') }}
                        </a>
                        <a href="{{ route('admin.novedades.index') }}" 
                           class="admin-nav__link {{ request()->routeIs('admin.novedades.*') ? 'admin-nav__link--active' : '' }}">
                            {{ __('Novedades') }}
                        </a>
                        <a href="{{ route('admin.reportes.index') }}" 
                           class="admin-nav__link {{ request()->routeIs('admin.reportes.*') ? 'admin-nav__link--active' : '' }}">
                            {{ __('Reportes') }}
                        </a>
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
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
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
                <a href="{{ route('admin.centros.index') }}" 
                   class="admin-nav__mobile-link {{ request()->routeIs('admin.centros.*') ? 'admin-nav__mobile-link--active' : '' }}">
                    {{ __('Centros') }}
                </a>
                <a href="{{ route('admin.programas.index') }}" 
                   class="admin-nav__mobile-link {{ request()->routeIs('admin.programas.*') ? 'admin-nav__mobile-link--active' : '' }}">
                    {{ __('Programas') }}
                </a>
                <a href="{{ route('admin.ofertas.index') }}" 
                   class="admin-nav__mobile-link {{ request()->routeIs('admin.ofertas.*') ? 'admin-nav__mobile-link--active' : '' }}">
                    {{ __('Ofertas') }}
                </a>
                <a href="{{ route('admin.preinscritos.index') }}" 
                   class="admin-nav__mobile-link {{ request()->routeIs('admin.preinscritos.*') ? 'admin-nav__mobile-link--active' : '' }}">
                    {{ __('Preinscritos') }}
                </a>
                <a href="{{ route('admin.novedades.index') }}" 
                   class="admin-nav__mobile-link {{ request()->routeIs('admin.novedades.*') ? 'admin-nav__mobile-link--active' : '' }}">
                    {{ __('Novedades') }}
                </a>
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
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
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