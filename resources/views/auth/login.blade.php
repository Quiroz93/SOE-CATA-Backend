@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="background"></div>
<div class="overlay"></div>

<div class="login-container">

    <!-- Columna izquierda -->
    <div class="left-panel">
        <img src="{{ asset('images/Logosimbolo-SENA.svg') }}" alt="SENA">
        <h1>Centro Agroempresarial y Turístico de los Andes</h1>
        <p>Sistema institucional para la gestión y publicación de ofertas educativas.</p>
    </div>

    <!-- Columna derecha -->
    <div class="right-panel">
        <div class="login-card">

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label>Correo institucional</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group password-wrapper">
                    <label>Contraseña</label>
                    <input type="password" name="password" id="password" required>
                    <span class="toggle-password" onclick="togglePassword()">Mostrar</span>

                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-group">
                    <input type="checkbox" name="remember">
                    Recordarme
                </div>

                @if (Route::has('password.request'))
                    <div class="forgot-password-group">
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    </div>
                @endif

                <button type="submit" class="login-button">
                    Iniciar sesión
                </button>

            </form>

        </div>
    </div>

</div>

<script>
function togglePassword() {
    const password = document.getElementById('password');
    const toggle = document.querySelector('.toggle-password');

    if (password.type === "password") {
        password.type = "text";
        toggle.textContent = "Ocultar";
    } else {
        password.type = "password";
        toggle.textContent = "Mostrar";
    }
}
</script>