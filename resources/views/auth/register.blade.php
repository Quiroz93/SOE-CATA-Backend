<x-guest-layout>
    <div class="background"></div>
    <div class="overlay"></div>

    <div class="register-container">
        <!-- Panel izquierdo institucional -->
        <div class="left-panel">
            <img src="{{ asset('images/Logosimbolo-SENA.svg') }}" alt="SENA Logo">
            <h1>Centro Agroempresarial y Turístico de los Andes</h1>
            <p>Sistema de gestión y publicación de ofertas educativas. Crea tu cuenta para acceder a todos los servicios de la plataforma.</p>
        </div>

        <!-- Panel derecho: formulario -->
        <div class="right-panel">
            <div class="register-card">
                <h2>Crear Cuenta</h2>
                <div class="subtitle">Ingresa tus datos para registrarte</div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Nombre Completo</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="password-requirements">
                        <ul>
                            <li>Mínimo 8 caracteres</li>
                            <li>Al menos una letra mayúscula</li>
                            <li>Al menos un número</li>
                        </ul>
                    </div>

                    <button type="submit" class="register-button">
                        Registrarse
                    </button>

                    <div class="login-link-group">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('login') }}" class="login-link">Iniciar Sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
