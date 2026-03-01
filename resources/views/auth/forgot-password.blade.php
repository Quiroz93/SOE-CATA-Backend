<x-guest-layout>
    <div class="background"></div>
    <div class="overlay"></div>

    <div class="forgot-password-container">
        <!-- Panel izquierdo institucional -->
        <div class="left-panel">
            <img src="/images/Logosimbolo-SENA.svg" alt="SENA Logo">
            <h1>Centro Agroempresarial y Turístico de los Andes</h1>
            <p>Sistema de gestión y publicación de ofertas educativas. Recupera tu contraseña de forma segura.</p>
        </div>

        <!-- Panel derecho: formulario -->
        <div class="right-panel">
            <div class="forgot-password-card">
                <h2>Recuperar Contraseña</h2>

                <div class="info-message">
                    ¿Olvidaste tu contraseña? No hay problema. Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="status-message">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="submit-button">
                        Enviar Enlace de Recuperación
                    </button>

                    <div class="back-link-group">
                        <a href="{{ route('login') }}" class="back-link">← Volver al inicio de sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
