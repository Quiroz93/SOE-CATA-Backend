<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENA | CATA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="welcome-container">
        <div class="welcome__logo">
            <img src="{{ asset('images/Logosimbolo-SENA.svg') }}" alt="Logosimbolo SENA">
        </div>
        <h1 class="welcome__title">Centro Agroempresarial y Turístico de los Andes</h1>
        <p class="welcome__description">Sistema de gestión y publicación de ofertas educativas</p>
        <nav class="welcome__actions">
            <a href="{{ route('login') }}" class="welcome__link">Iniciar sesión</a>
        </nav>
    </div>
</body>
</html>