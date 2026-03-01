<x-guest-layout>
    <div class="background"></div>
    <div class="overlay"></div>

    <div class="verify-email-container">
        <!-- Panel izquierdo institucional -->
        <div class="left-panel">
            <img src="{{ asset('images/Logosimbolo-SENA.svg') }}" alt="SENA Logo">
            <h1>Centro Agroempresarial y Turístico de los Andes</h1>
            <p>Sistema de gestión y publicación de ofertas educativas. Verifica tu correo electrónico para continuar.</p>
        </div>

        <!-- Panel derecho: formulario -->
        <div class="right-panel">
            <div class="verify-email-card">
                <div class="email-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <h2>Verificar Email</h2>

                <div class="info-message">
                    ¡Gracias por registrarte! Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar? Si no recibiste el correo, con gusto te enviaremos otro.
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="success-message">
                        ¡Perfecto! Un nuevo enlace de verificación ha sido enviado a tu correo electrónico.
                    </div>
                @endif

                <div class="divider"></div>

                <div class="instruction-list">
                    <h3>Instrucciones:</h3>
                    <ul>
                        <li>Revisa tu bandeja de entrada</li>
                        <li>Haz clic en el enlace de verificación</li>
                        <li>Si no lo encuentras, revisa spam o correo no deseado</li>
                    </ul>
                </div>

                <div class="action-buttons">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="primary-button">
                            Reenviar Email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="secondary-button">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
