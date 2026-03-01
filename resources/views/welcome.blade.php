<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENA | CATA</title>
        <!-- CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="centered-container">
        <div class="logo">
            <img src="/images/Logosimbolo-SENA.svg" alt="Logosimbolo SENA">
            <div class="center-name">Centro Agroempresarial y Turístico de los Andes</div>
        </div>
        <div class="system-desc">Sistema de gestión y publicación de ofertas educativas</div>
        <div class="actions">
            <a href="{{ route('login') }}" class="btn">Iniciar sesión</a>
        </div>
    </div>
</body>
</html>