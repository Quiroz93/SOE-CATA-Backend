@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Dashboard Administrativo</h1>
    </div>

    <!-- Encabezado Institucional -->
    <div class="institutional-header">
        <div class="institutional-header__content">
            <div class="institutional-header__logo">
                <img src="/images/Logosimbolo-SENA.svg" alt="Logo SENA" class="institutional-logo">
            </div>
            <div class="institutional-header__info">
                <h2 class="institutional-header__title">Centro Agroempresarial y Turístico de los Andes</h2>
                <p class="institutional-header__subtitle">Servicio Nacional de Aprendizaje - SENA</p>
            </div>
        </div>
    </div>

    <!-- Estadísticas en Tiempo Real (Excel Upload) -->
    <div class="stats-live-section">
        <div class="stats-live-card">
            <div class="stats-report-tabs" id="statsReportTabs">
                <button type="button" class="stats-tab active" data-report-kind="general_inscripciones">
                    REPORTE_DE_INSCRIPCIONES GENERALES
                </button>
                <button type="button" class="stats-tab" data-report-kind="individual_ficha">
                    REPORTE_DE_INSCRIPCIONES INDIVIDUAL POR FICHA
                </button>
                <button type="button" class="stats-tab" data-report-kind="consolidador">
                    CONSOLIDADOR DE FICHAS EXCEL
                </button>
            </div>

            <div class="stats-live-header">
                <div>
                    <h2 class="stats-live-title" id="statsLiveTitle">Estadísticas en Tiempo Real por COD_FICHA</h2>
                    <p class="stats-live-subtitle" id="statsLiveSubtitle">Compara por ficha el CUPO contra INSCRITOS PRIMERA y SEGUNDA OPCIÓN, con porcentaje de demanda y sobrecupo</p>
                </div>
                <div id="chartTypeControl">
                    <select id="reportType" class="stats-select">
                        <option value="bar">📊 Gráfica de Barras</option>
                        <option value="line">📈 Gráfica de Líneas</option>
                        <option value="pie">🥧 Gráfica Circular</option>
                    </select>
                </div>
                <div id="chartTypeControlIndividual" style="display: none;">
                    <select id="individualChartType" class="stats-select">
                        <option value="bar">📊 Gráfica de Barras</option>
                        <option value="line">📈 Gráfica de Líneas</option>
                        <option value="doughnut">🍩 Gráfica Donut</option>
                        <option value="pie">🥧 Gráfica Circular</option>
                    </select>
                </div>
            </div>

            <div class="stats-upload-zone" id="dropZone">
                <svg class="stats-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <h3 class="stats-upload-title">Arrastra aquí tu archivo Excel</h3>
                <p class="stats-upload-text">o haz clic para seleccionar</p>
                <p class="stats-upload-hint">Formatos: .xls, .xlsx (máx. 10MB)</p>
                <input type="file" id="excelFile" accept=".xls,.xlsx" hidden>
            </div>

            <div class="stats-status" id="statusText"></div>

            <!-- Metadata Summary -->
            <div class="stats-metadata" id="statsMetadata" style="display: none;">
                <div class="metadata-grid">
                    <div class="metadata-item">
                        <span class="metadata-label" id="metaLabel1">Total Fichas:</span>
                        <span class="metadata-value" id="metaValue1">0</span>
                    </div>
                    <div class="metadata-item">
                        <span class="metadata-label" id="metaLabel2">Total Inscritos:</span>
                        <span class="metadata-value" id="metaValue2">0</span>
                    </div>
                    <div class="metadata-item">
                        <span class="metadata-label" id="metaLabel3">Total Cupos:</span>
                        <span class="metadata-value" id="metaValue3">0</span>
                    </div>
                    <div class="metadata-item">
                        <span class="metadata-label" id="metaLabel4">Ocupación Promedio:</span>
                        <span class="metadata-value" id="metaValue4">0%</span>
                    </div>
                </div>
            </div>

            <div class="stats-results" id="statsResults" style="display: none;">
                <div id="statsResultsGeneral">
                    <div class="stats-results-grid">
                        <div id="programChartContainer" class="stats-chart-container"></div>
                        <div class="stats-table-container">
                            <h4 class="stats-table-title">Resumen de Demanda por COD_FICHA</h4>
                            <div class="stats-table-wrapper">
                                <table class="stats-table">
                                    <thead>
                                        <tr>
                                            <th>COD_FICHA</th>
                                            <th>Programa</th>
                                            <th>Cupos</th>
                                            <th>Primera Opción</th>
                                            <th>Segunda Opción</th>
                                            <th>% Demanda</th>
                                            <th>Sobrecupo (1ra)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="statsTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="stats-comparison">
                        <h4 class="stats-comparison-title">Comparativo de Estados por COD_FICHA</h4>
                        <div class="stats-comparison-wrapper">
                            <table class="stats-comparison-table" id="comparisonTable">
                                <thead></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="stats-demand-chart">
                        <h4 class="stats-comparison-title">Porcentaje de Demanda por COD_FICHA (Primera Opción / Cupo)</h4>
                        <div class="stats-demand-chart-wrapper">
                            <canvas id="demandChart"></canvas>
                        </div>
                    </div>
                </div>

                <div id="statsResultsIndividual" style="display: none;">
                    <div class="stats-results-grid">
                        <div class="stats-chart-container">
                            <canvas id="individualStateChart"></canvas>
                        </div>
                        <div class="stats-table-container">
                            <h4 class="stats-table-title">Totales por Estado</h4>
                            <div class="stats-table-wrapper">
                                <table class="stats-table">
                                    <thead>
                                        <tr>
                                            <th>Estado</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="individualStatesTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="stats-comparison">
                        <h4 class="stats-comparison-title">Detalle de Aprendices de la Ficha</h4>
                        <div class="stats-comparison-wrapper">
                            <table class="stats-comparison-table">
                                <thead>
                                    <tr>
                                        <th>Identificación</th>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="individualTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="statsResultsConsolidador" style="display: none;">
                    <div class="stats-metadata" id="consolidadorMetadata" style="display: none;">
                        <div class="metadata-grid">
                            <div class="metadata-item">
                                <span class="metadata-label" id="consolidadorMetaLabel1">Total Fichas:</span>
                                <span class="metadata-value" id="consolidadorMetaValue1">0</span>
                            </div>
                            <div class="metadata-item">
                                <span class="metadata-label" id="consolidadorMetaLabel2">Total Aprendices:</span>
                                <span class="metadata-value" id="consolidadorMetaValue2">0</span>
                            </div>
                            <div class="metadata-item">
                                <span class="metadata-label" id="consolidadorMetaLabel3">Total Cupos:</span>
                                <span class="metadata-value" id="consolidadorMetaValue3">0</span>
                            </div>
                            <div class="metadata-item">
                                <span class="metadata-label" id="consolidadorMetaLabel4">Ocupación Promedio:</span>
                                <span class="metadata-value" id="consolidadorMetaValue4">0%</span>
                            </div>
                        </div>
                    </div>

                    <div class="stats-table-container">
                        <h4 class="stats-table-title">Totales por Estado</h4>
                        <div class="stats-table-wrapper">
                            <table class="stats-table">
                                <thead>
                                    <tr>
                                        <th>Estado</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="consolidadorStatesTableBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="stats-comparison">
                        <h4 class="stats-comparison-title">Detalle de Fichas Consolidadas</h4>
                        <div class="stats-comparison-wrapper">
                            <table class="stats-comparison-table">
                                <thead>
                                    <tr>
                                        <th>Ficha</th>
                                        <th>Programa</th>
                                        <th>Inscritos</th>
                                        <th>Estado Detallado</th>
                                    </tr>
                                </thead>
                                <tbody id="consolidadorFichasTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="dashboard-activity">
        <div class="activity-card">
            <h3 class="activity-title">Actividad Reciente</h3>
            <ul class="activity-list">
                @foreach($actividades as $actividad)
                    <li class="activity-item">
                        <span class="activity-dot"></span>
                        <span class="activity-text">{{ $actividad }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
/* Encabezado Institucional */
.institutional-header {
    background: linear-gradient(135deg, #39A900 0%, #2d8400 100%);
    color: white;
    padding: 40px 20px;
    margin-bottom: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(57, 169, 0, 0.2);
}

.institutional-header__content {
    display: flex;
    align-items: center;
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.institutional-header__logo {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.institutional-logo {
    width: 100px;
    height: 100px;
    opacity: 0.95;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}

.institutional-header__info {
    flex: 1;
}

.institutional-header__title {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: white;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.institutional-header__subtitle {
    font-size: 14px;
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .institutional-header {
        padding: 30px 15px;
    }

    .institutional-header__content {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }

    .institutional-header__title {
        font-size: 22px;
    }

    .institutional-logo {
        width: 80px;
        height: 80px;
    }
}

/* Dashboard KPIs - Deprecated but kept for reference */
.dashboard-kpis {
    display: none;
}

.kpi-card {
    display: none;
}

.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.kpi-label {
    font-size: 14px;
    font-weight: 500;
    color: #666;
}

.kpi-icon {
    width: 24px;
    height: 24px;
    stroke-width: 2;
    opacity: 0.6;
}

.kpi-value {
    font-size: 32px;
    font-weight: 700;
    color: #333;
}

.kpi-primary { border-left: 4px solid #39A900; }
.kpi-success { border-left: 4px solid #10b981; }
.kpi-warning { border-left: 4px solid #f59e0b; }
.kpi-secondary { border-left: 4px solid #6b7280; }

/* Stats Metadata */
.stats-metadata {
    margin: 20px 0;
    padding: 16px;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-radius: 8px;
    border-left: 4px solid #39A900;
}

.metadata-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.metadata-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.metadata-label {
    font-size: 12px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.metadata-value {
    font-size: 24px;
    font-weight: 700;
    color: #00304D;
}

/* Stats Live Section */
.stats-live-section {
    margin-bottom: 24px;
}

.stats-live-card {
    background: white;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.stats-report-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}

.stats-tab {
    border: 1px solid #d1d5db;
    background: #f9fafb;
    color: #374151;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.stats-tab.active {
    border-color: #39A900;
    background: #ecfdf5;
    color: #14532d;
}

.stats-live-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.stats-live-title {
    font-size: 20px;
    font-weight: 700;
    color: #00304D;
    margin: 0 0 4px 0;
}

.stats-live-subtitle {
    font-size: 14px;
    color: #666;
    margin: 0;
}

.stats-select {
    padding: 8px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 14px;
    background: white;
    cursor: pointer;
}

.stats-upload-zone {
    border: 2px dashed #39A900;
    border-radius: 12px;
    padding: 48px 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.stats-upload-zone:hover,
.stats-upload-zone.dragover {
    background: #f0fdf4;
    border-color: #16a34a;
}

.stats-upload-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    stroke: #39A900;
    stroke-width: 2;
}

.stats-upload-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0 0 8px 0;
}

.stats-upload-text {
    font-size: 14px;
    color: #666;
    margin: 0 0 4px 0;
}

.stats-upload-hint {
    font-size: 12px;
    color: #999;
    margin: 0;
}

.stats-status {
    margin: 16px 0;
    padding: 12px;
    border-radius: 6px;
    font-size: 14px;
    text-align: center;
}

.stats-status.loading {
    background: #dbeafe;
    color: #1e40af;
}

.stats-status.success {
    background: #d1fae5;
    color: #065f46;
}

.stats-status.error {
    background: #fee2e2;
    color: #991b1b;
}

.stats-results {
    margin-top: 32px;
}

.stats-results-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
}

@media (max-width: 1024px) {
    .stats-results-grid {
        grid-template-columns: 1fr;
    }
}

.stats-chart-container {
    background: #f9fafb;
    border-radius: 8px;
    padding: 20px;
    height: 420px;
    overflow: hidden;
}

.stats-chart-container canvas {
    width: 100% !important;
    height: 100% !important;
    max-width: 100%;
    display: block;
}

.stats-table-container {
    background: #f9fafb;
    border-radius: 8px;
    padding: 20px;
}

.stats-table-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0 0 16px 0;
}

.stats-table-wrapper {
    max-height: 400px;
    overflow-y: auto;
}

.stats-table {
    width: 100%;
    border-collapse: collapse;
}

.stats-table th,
.stats-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.stats-table th {
    background: white;
    font-weight: 600;
    font-size: 13px;
    color: #666;
    position: sticky;
    top: 0;
}

.stats-table td {
    font-size: 13px;
    color: #333;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}

.stats-comparison {
    background: #f9fafb;
    border-radius: 8px;
    padding: 20px;
}

.stats-demand-chart {
    background: #f9fafb;
    border-radius: 8px;
    padding: 20px;
    margin-top: 24px;
}

.stats-demand-chart-wrapper {
    background: white;
    border-radius: 8px;
    padding: 16px;
    height: 360px;
    overflow: hidden;
}

.stats-demand-chart-wrapper canvas {
    width: 100% !important;
    height: 100% !important;
    max-width: 100%;
    display: block;
}

.stats-comparison-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0 0 16px 0;
}

.stats-comparison-wrapper {
    overflow-x: auto;
}

.stats-comparison-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.stats-comparison-table th,
.stats-comparison-table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #e5e7eb;
}

.stats-comparison-table th {
    background: white;
    font-weight: 600;
    color: #666;
}

.stats-comparison-table td {
    background: white;
    color: #333;
}

/* Activity */
.dashboard-activity {
    margin-bottom: 32px;
}

.activity-card {
    background: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.activity-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0 0 16px 0;
}

.activity-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #39A900;
    margin-right: 12px;
}

.activity-text {
    font-size: 14px;
    color: #666;
}
</style>
@endsection

@section('scripts')
@vite(['resources/js/admin/dashboard-stats.js'])
@endsection
