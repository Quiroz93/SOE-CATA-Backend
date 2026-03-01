@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/reportes.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="reportes-container">
    <!-- Header -->
    <div class="reportes-header">
        <h1 class="reportes-title">Reportes Administrativos</h1>
        <p class="reportes-subtitle">Visualiza métricas y estadísticas del sistema</p>
    </div>

    <!-- Filtros -->
    <div class="filtros-section">
        <form method="GET" action="{{ route('admin.reportes.index') }}" class="filtros-form">
            <div class="filtro-group">
                <label for="start_date">Desde:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="filtro-input">
            </div>
            <div class="filtro-group">
                <label for="end_date">Hasta:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="filtro-input">
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
    </div>

    <!-- Data container for Chart.js script -->
    <div
        id="dashboardData"
        data-meses='@json($meses)'
        data-ofertas-por-mes='@json($ofertasPorMes)'
        data-ofertas-activas="{{ $ofertasActivas }}"
        data-ofertas-vencidas="{{ $ofertasVencidas }}"
        data-inactivas="{{ $ofertasPorEstado['inactiva'] }}"
        style="display: none;"
    ></div>

    <!-- KPI Cards -->
    <div class="kpi-grid">
        <div class="kpi-card kpi-primary">
            <div class="kpi-header">
                <span class="kpi-label">Total Ofertas</span>
                <svg class="kpi-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5"></path>
                    <path d="M2 12l10 5 10-5"></path>
                </svg>
            </div>
            <div class="kpi-value">{{ $totalOfertas }}</div>
        </div>

        <div class="kpi-card kpi-secondary">
            <div class="kpi-header">
                <span class="kpi-label">Ofertas Activas</span>
                <svg class="kpi-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div class="kpi-value kpi-success">{{ $ofertasActivas }}</div>
        </div>

        <div class="kpi-card kpi-secondary">
            <div class="kpi-header">
                <span class="kpi-label">Ofertas Vencidas</span>
                <svg class="kpi-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="kpi-value kpi-warning">{{ $ofertasVencidas }}</div>
        </div>

        <div class="kpi-card kpi-secondary">
            <div class="kpi-header">
                <span class="kpi-label">Total Usuarios</span>
                <svg class="kpi-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="kpi-value">{{ $totalUsuarios }}</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-card">
            <div class="chart-header">
                <h3>Ofertas por Mes</h3>
            </div>
            <canvas id="ofertasMesChart" height="80"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <h3>Distribución por Estado</h3>
            </div>
            <canvas id="estadoOfertasChart" height="80"></canvas>
        </div>
    </div>

    <!-- Data Tables Section -->
    <div class="tables-section">
        <div class="table-card">
            <div class="table-header">
                <h3>Centros con Más Ofertas</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Centro</th>
                        <th>Ofertas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($centrosMasOfertas as $centro)
                        <tr>
                            <td>{{ $centro->nombre }}</td>
                            <td><span class="badge badge-primary">{{ $centro->ofertas_count }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h3>Programas Más Demandados</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Programa</th>
                        <th>Ofertas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programasMasDemandados as $programa)
                        <tr>
                            <td>{{ $programa->nombre }}</td>
                            <td><span class="badge badge-success">{{ $programa->ofertas_count }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Offers -->
    <div class="recent-section">
        <div class="table-card">
            <div class="table-header">
                <h3>Ofertas Recientes</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ofertasRecientes as $oferta)
                        <tr>
                            <td>{{ $oferta->nombre }}</td>
                            <td>
                                <span class="status-badge status-{{ $oferta->estado }}">
                                    {{ ucfirst($oferta->estado) }}
                                </span>
                            </td>
                            <td>{{ $oferta->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-4">No hay ofertas recientes</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@vite('resources/js/admin/reportes.js')
@endsection
