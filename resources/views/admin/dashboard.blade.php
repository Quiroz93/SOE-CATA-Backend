@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-container">

        <!-- Hidden data container for JavaScript -->
        <div id="dashboardData" 
             class="dashboard-data"
             data-meses='{{ json_encode($meses) }}'
             data-ofertas-por-mes='{{ json_encode($ofertasPorMes) }}'
             data-ofertas-activas='{{ $ofertasActivas }}'
             data-ofertas-vencidas='{{ $ofertasVencidas }}'>
        </div>

        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard General</h1>
        </div>

        <!-- KPI Cards -->
        <div class="dashboard-kpi-grid">
            <div class="dashboard-card">
                <h3 class="dashboard-card__label">Total Usuarios</h3>
                <div class="dashboard-card__value">{{ $totalUsuarios }}</div>
            </div>
            <div class="dashboard-card">
                <h3 class="dashboard-card__label">Total Ofertas</h3>
                <div class="dashboard-card__value">{{ $totalOfertas }}</div>
            </div>
            <div class="dashboard-card">
                <h3 class="dashboard-card__label">Ofertas Activas</h3>
                <div class="dashboard-card__value dashboard-card__value--success">{{ $ofertasActivas }}</div>
            </div>
            <div class="dashboard-card">
                <h3 class="dashboard-card__label">Ofertas Vencidas</h3>
                <div class="dashboard-card__value dashboard-card__value--danger">{{ $ofertasVencidas }}</div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="dashboard-charts-grid spacing-2">
            <div class="dashboard-card__chart">
                <h3 class="dashboard-card__title">Ofertas por Mes</h3>
                <canvas id="ofertasMes" class="dashboard-chart-canvas"></canvas>
            </div>

            <div class="dashboard-card__chart">
                <h3 class="dashboard-card__title">Estado de Ofertas</h3>
                <canvas id="estadoOfertas" class="dashboard-chart-canvas"></canvas>
            </div>
        </div>

        <!-- Activity Section -->
        <div class="dashboard-activity">
            <h3 class="dashboard-activity__title">Actividad Reciente</h3>
            <ul class="dashboard-activity__list">
                @foreach($actividades as $actividad)
                    <li class="dashboard-activity__item">{{ $actividad }}</li>
                @endforeach
            </ul>
        </div>

    </div>
@endsection

@section('scripts')
    @vite(['resources/js/admin/dashboard.js'])
@endsection