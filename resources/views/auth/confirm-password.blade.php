<x-guest-layout>
    <div class="background"></div>
    <div class="overlay"></div>

    <div class="confirm-password-container">
        <!-- Panel izquierdo institucional -->
        <div class="left-panel">
            <img src="/images/Logosimbolo-SENA.svg" alt="SENA Logo">
            <h1>Centro Agroempresarial y Turístico de los Andes</h1>
            <p>Sistema de gestión y publicación de ofertas educativas. Área segura del sistema.</p>
        </div>

        <!-- Panel derecho: formulario -->
        <div class="right-panel">
            <div class="confirm-password-card">
                <div class="security-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <h2>Confirmar Contraseña</h2>

                <div class="info-message">
                    Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="submit-button">
                        Confirmar
                    </button>

                    <div class="cancel-link-group">
                        <a href="{{ route('dashboard') }}" class="cancel-link">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
