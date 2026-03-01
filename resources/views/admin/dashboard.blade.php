@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="dashboard-admin-content">

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
@endsection
