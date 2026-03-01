<x-guest-layout>
    <div class="background"></div>
    <div class="overlay"></div>

    <div class="reset-password-container">
        <!-- Panel izquierdo institucional -->
        <div class="left-panel">
            <img src="/images/Logosimbolo-SENA.svg" alt="SENA Logo">
            <h1>Centro Agroempresarial y Turístico de los Andes</h1>
            <p>Sistema de gestión y publicación de ofertas educativas. Restablece tu contraseña de forma segura.</p>
        </div>

        <!-- Panel derecho: formulario -->
        <div class="right-panel">
            <div class="reset-password-card">
                <h2>Restablecer Contraseña</h2>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Nueva Contraseña</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
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

                    <button type="submit" class="submit-button">
                        Restablecer Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
