@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@vite('resources/js/admin/dashboard.js')

<div class="main-content">

        <div class="dashboard-header">
            <h1>Dashboard General</h1>
        </div>

        <!-- KPI -->
        <div class="cards">
            <div class="card">
                <h3>Total Usuarios</h3>
                <div class="number">{{ $totalUsuarios }}</div>
            </div>
            <div class="card">
                <h3>Total Ofertas</h3>
                <div class="number">{{ $totalOfertas }}</div>
            </div>
            <div class="card">
                <h3>Ofertas Activas</h3>
                <div class="number">{{ $ofertasActivas }}</div>
            </div>
            <div class="card">
                <h3>Ofertas Vencidas</h3>
                <div class="number">{{ $ofertasVencidas }}</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts">
            <div
                id="dashboardData"
                data-meses='@json($meses)'
                data-ofertas-por-mes='@json($ofertasPorMes)'
                data-ofertas-activas="{{ $ofertasActivas }}"
                data-ofertas-vencidas="{{ $ofertasVencidas }}"
            ></div>

            <div class="chart-card">
                <h3>Ofertas por Mes</h3>
                <canvas id="ofertasMes"></canvas>
            </div>

            <div class="chart-card">
                <h3>Estado de Ofertas</h3>
                <canvas id="estadoOfertas"></canvas>
            </div>
        </div>

        <!-- Actividad -->
        <div class="activity">
            <h3>Actividad Reciente</h3>
            <ul>
                @foreach($actividades as $actividad)
                    <li>{{ $actividad }}</li>
                @endforeach
            </ul>
        </div>

</div>
@endsection

