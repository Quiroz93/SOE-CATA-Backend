<header class="topbar" aria-label="Barra superior administrativa">
    <div class="topbar-brand">
        <img src="{{ asset('images/Logosimbolo-SENA.svg') }}" alt="Logo símbolo SENA" class="topbar-logo">
        <span class="topbar-title">Panel Administrativo SENA</span>
    </div>

    <div class="topbar-actions">
        <details class="profile-menu">
            <summary aria-label="Abrir menú de perfil">
                <span class="profile-name">{{ auth()->user()?->name ?? 'Administrador' }}</span>
                <i class="fas fa-chevron-down"></i>
            </summary>
            <div class="profile-dropdown" role="menu" aria-label="Menú de perfil">
                <a href="{{ route('profile.edit') }}" role="menuitem">
                    <i class="fas fa-user"></i> Administrar perfil
                </a>
                <a href="{{ route('profile.edit') }}" role="menuitem">
                    <i class="fas fa-user-cog"></i> Configuración de perfil
                </a>
            </div>
        </details>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn" aria-label="Cerrar sesión">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </button>
        </form>
    </div>
</header>
