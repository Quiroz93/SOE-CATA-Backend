@extends('layouts.app')

@section('title', 'Bienvenido Administrador')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-white">
    <div class="max-w-xl w-full p-8 rounded-lg shadow-lg border border-gray-100 bg-white">
        <div class="flex items-center justify-center mb-6">
            <img src="/assets/sena-logo.svg" alt="SENA Logo" class="h-16 mr-4" />
            <h1 class="text-3xl font-bold text-[#39A900]">Bienvenido(a) Administrador(a)</h1>
        </div>
        <p class="text-lg text-gray-700 mb-4 text-center">
            Plataforma de Gestión Administrativa SENA
        </p>
        <div class="mb-6">
            <ul class="space-y-2 text-gray-800">
                <li><span class="font-semibold text-[#007832]">✔ Gestión de ofertas educativas</span></li>
                <li><span class="font-semibold text-[#007832]">✔ Administración de usuarios y roles</span></li>
                <li><span class="font-semibold text-[#007832]">✔ Reportes y estadísticas en tiempo real</span></li>
                <li><span class="font-semibold text-[#007832]">✔ Cumplimiento de identidad institucional</span></li>
            </ul>
        </div>
        <div class="flex justify-center">
            <a href="{{ route('admin.programas.index') }}" class="bg-[#39A900] hover:bg-[#007832] text-white font-bold py-2 px-6 rounded transition-colors">
                Ir al Panel Principal
            </a>
        </div>
    </div>
    <footer class="mt-8 text-sm text-gray-500 text-center">
        &copy; 2026 SENA. Todos los derechos reservados.
    </footer>
</div>
@endsection
