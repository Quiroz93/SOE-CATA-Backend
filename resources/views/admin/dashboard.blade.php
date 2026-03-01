@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="dashboard-wrapper">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Panel Admin</h2>

        <a href="{{ route('admin.dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}">
            <i class="fas fa-users"></i> Usuarios
        </a>
        <a href="{{ route('admin.ofertas.index') }}">
            <i class="fas fa-briefcase"></i> Ofertas
        </a>
        <a href="{{ route('admin.centros.index') }}">
            <i class="fas fa-building"></i> Centros
        </a>
        <a href="{{ route('admin.welcome') }}">
            <i class="fas fa-cog"></i> Configuración
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Contenido -->
    <div class="main-content">

        <div class="dashboard-header">
            <h1>Bienvenido, {{ auth()->user()->name ?? 'Administrador' }}</h1>
        </div>

        <!-- Tarjetas métricas -->
        <div class="cards">
            <div class="card">
                <h3>Total Usuarios</h3>
                <div class="number">{{ $totalUsuarios ?? 125 }}</div>
            </div>

            <div class="card">
                <h3>Total Ofertas</h3>
                <div class="number">{{ $totalOfertas ?? 48 }}</div>
            </div>

            <div class="card">
                <h3>Ofertas Activas</h3>
                <div class="number">{{ $ofertasActivas ?? 32 }}</div>
            </div>

            <div class="card">
                <h3>Ofertas Vencidas</h3>
                <div class="number">{{ $ofertasVencidas ?? 16 }}</div>
            </div>
        </div>

        <!-- Actividad reciente -->
        <div class="activity">
            <h3>Actividad reciente</h3>
            <ul>
                @if(isset($actividadReciente) && count($actividadReciente) > 0)
                    @foreach($actividadReciente as $actividad)
                        <li>{{ $actividad }}</li>
                    @endforeach
                @else
                    <li>Nuevo usuario registrado</li>
                    <li>Oferta "Análisis de Software" publicada</li>
                    <li>Oferta "Turismo Rural" actualizada</li>
                    <li>Usuario administrador editó configuración</li>
                @endif
            </ul>
        </div>

    </div>

</div>
@endsection
