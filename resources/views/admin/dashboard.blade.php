@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="admin-page">
    <!-- Botón Manual de Usuario (Discreto) -->
    <button id="userManualBtn" class="user-manual-btn" title="Manual de Usuario">
        <svg class="manual-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            <path d="M8 7h8"></path>
            <path d="M8 11h8"></path>
            <path d="M8 15h4"></path>
        </svg>
    </button>

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
                    📊 GRAFICAR REPORTE GENERAL DE INSCRIPCIONES
                </button>
                <button type="button" class="stats-tab" data-report-kind="individual_ficha">
                    📊 GRAFICAR REPORTE DE INSCRIPCIONES POR FICHA
                </button>
                <button type="button" class="stats-tab" data-report-kind="consolidador">
                    CONSOLIDAR INFORMES EXCEL
                </button>
                <button type="button" class="stats-tab" data-report-kind="generar_graficas_manual">
                    📊 GENERAR GRÁFICAS MANUALMENTE
                </button>
            </div>

            <div class="stats-live-header">
                <div>
                    <h2 class="stats-live-title" id="statsLiveTitle">Estadistica general</h2>
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
                <div id="downloadButtonsContainer" style="display: none;"></div>
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

                    <!-- Sección de Gráficas Individuales por Ficha -->
                    <div id="individualFichasChartsContainer" style="display: none; margin-top: 40px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                            <h3 style="color: #333; font-size: 18px; font-weight: 600; margin: 0;">
                                Análisis Detallado por Ficha
                            </h3>
                            <select id="fichaChartType" class="stats-select">
                                <option value="doughnut">🍩 Gráfica Donut</option>
                                <option value="pie">🥧 Gráfica Circular</option>
                                <option value="bar">📊 Gráfica de Barras</option>
                                <option value="line">📈 Gráfica de Líneas</option>
                            </select>
                        </div>
                        <div id="individualFichasChartsGrid" style="
                            display: grid;
                            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
                            gap: 30px;
                            margin-bottom: 40px;
                        "></div>
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

                <!-- NUEVA PESTAÑA: Generar Gráficas Manualmente con Análisis Dinámico de Excel -->
                <div id="genericChartFormContainer" style="display: block; background: white; padding: 20px; border-radius: 8px; margin-top: 20px;">
                    
                    <!-- Indicador de Progreso -->
                    <div id="dynamicChartProgress" style="display: flex; justify-content: space-between; margin-bottom: 30px; padding: 0 10%;">
                        <div class="progress-step active" data-step="1">
                            <div class="progress-circle">1</div>
                            <div class="progress-label">Cargar Archivo</div>
                        </div>
                        <div class="progress-step" data-step="2">
                            <div class="progress-circle">2</div>
                            <div class="progress-label">Análisis de Datos</div>
                        </div>
                        <div class="progress-step" data-step="3">
                            <div class="progress-circle">3</div>
                            <div class="progress-label">Selección</div>
                        </div>
                        <div class="progress-step" data-step="4">
                            <div class="progress-circle">4</div>
                            <div class="progress-label">Configuración</div>
                        </div>
                        <div class="progress-step" data-step="5">
                            <div class="progress-circle">5</div>
                            <div class="progress-label">Vista Previa</div>
                        </div>
                        <div class="progress-step" data-step="6">
                            <div class="progress-circle">6</div>
                            <div class="progress-label">Resultados</div>
                        </div>
                    </div>

                    <!-- Paso 1: Zona de Carga de Archivo -->
                    <div id="dynamicStep1" class="dynamic-step" style="display: block;">
                        <div style="padding: 25px; background: #f9f9f9; border-radius: 8px; border: 2px solid #39a900;">
                            <h3 style="color: #333; font-size: 18px; font-weight: 700; margin-bottom: 15px; text-align: center;">
                                📤 Paso 1: Cargar Archivo Excel
                            </h3>
                            <p style="text-align: center; color: #666; margin-bottom: 25px;">
                                Carga cualquier archivo Excel. El sistema analizará automáticamente su estructura y contenido.
                            </p>
                            
                            <div class="stats-upload-zone" id="dynamicDropZone" style="cursor: pointer;">
                                <svg class="stats-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                <h3 class="stats-upload-title">Arrastra aquí tu archivo Excel</h3>
                                <p class="stats-upload-text">o haz clic para seleccionar</p>
                                <p class="stats-upload-hint">Formatos: .xls, .xlsx (máx. 10MB)</p>
                                <input type="file" id="dynamicExcelFile" accept=".xls,.xlsx" hidden>
                            </div>

                            <div id="dynamicUploadStatus" style="margin-top: 20px; text-align: center;"></div>
                        </div>
                    </div>

                    <!-- Paso 2: Análisis y Resumen de Datos -->
                    <div id="dynamicStep2" class="dynamic-step" style="display: none;">
                        <div style="padding: 25px; background: #f9f9f9; border-radius: 8px; border: 2px solid #39a900;">
                            <h3 style="color: #333; font-size: 18px; font-weight: 700; margin-bottom: 15px;">
                                🔍 Paso 2: Análisis de Datos del Archivo
                            </h3>
                            
                            <div id="fileAnalysisSummary" style="margin-bottom: 25px;"></div>
                            
                            <div style="display: flex; justify-content: center; gap: 10px;">
                                <button id="btnPrevStep2" class="btn-secondary">← Anterior</button>
                                <button id="btnNextStep2" class="btn-primary">Continuar →</button>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 3: Selección de Datos -->
                    <div id="dynamicStep3" class="dynamic-step" style="display: none;">
                        <div style="padding: 25px; background: #f9f9f9; border-radius: 8px; border: 2px solid #39a900;">
                            <h3 style="color: #333; font-size: 18px; font-weight: 700; margin-bottom: 15px;">
                                ✓ Paso 3: Selecciona los Datos para la Gráfica
                            </h3>
                            
                            <div id="dataSelectionForm" style="margin-bottom: 25px;"></div>
                            
                            <div style="display: flex; justify-content: center; gap: 10px;">
                                <button id="btnPrevStep3" class="btn-secondary">← Anterior</button>
                                <button id="btnNextStep3" class="btn-primary">Continuar →</button>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 4: Configuración de Gráfica -->
                    <div id="dynamicStep4" class="dynamic-step" style="display: none;">
                        <div style="padding: 25px; background: #f9f9f9; border-radius: 8px; border: 2px solid #39a900;">
                            <h3 style="color: #333; font-size: 18px; font-weight: 700; margin-bottom: 15px;">
                                ⚙️ Paso 4: Configura tu Gráfica
                            </h3>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
                                        Título de la Gráfica <span style="color: #FF4444;">*</span>
                                    </label>
                                    <input type="text" id="dynamicChartTitle" placeholder="Ej: Reporte de Inscripciones por Ficha" 
                                        style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 4px;">
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
                                        Tipo de Gráfica <span style="color: #FF4444;">*</span>
                                    </label>
                                    <select id="dynamicChartType" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 4px;">
                                        <option value="bar">📊 Barras</option>
                                        <option value="line">📈 Líneas</option>
                                        <option value="pie">🥧 Circular</option>
                                        <option value="doughnut">🍩 Donut</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
                                        Título de Tabla <span style="color: #FF4444;">*</span>
                                    </label>
                                    <input type="text" id="dynamicTableTitle" placeholder="Ej: Resumen de Datos" 
                                        style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 4px;">
                                </div>
                                <div>
                                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
                                        Color Principal
                                    </label>
                                    <input type="color" id="dynamicChartColor" value="#39a900" 
                                        style="width: 100%; height: 44px; border: 2px solid #ddd; border-radius: 4px; cursor: pointer;">
                                </div>
                            </div>
                            
                            <div style="display: flex; justify-content: center; gap: 10px;">
                                <button id="btnPrevStep4" class="btn-secondary">← Anterior</button>
                                <button id="btnNextStep4" class="btn-primary">Continuar →</button>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 5: Vista Previa -->
                    <div id="dynamicStep5" class="dynamic-step" style="display: none;">
                        <div style="padding: 25px; background: #f9f9f9; border-radius: 8px; border: 2px solid #39a900;">
                            <h3 style="color: #333; font-size: 18px; font-weight: 700; margin-bottom: 15px;">
                                👁️ Paso 5: Vista Previa de Datos Seleccionados
                            </h3>
                            
                            <div id="dataPreviewContainer" style="margin-bottom: 25px;"></div>
                            
                            <div style="display: flex; justify-content: center; gap: 10px;">
                                <button id="btnPrevStep5" class="btn-secondary">← Ajustar</button>
                                <button id="btnGenerateChart" class="btn-success">✓ Generar Gráfica</button>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 6: Resultados -->
                    <div id="dynamicStep6" class="dynamic-step" style="display: none;">
                        <div style="padding: 25px; background: white; border-radius: 8px; border: 2px solid #e0e0e0;">
                            <h3 style="color: #333; font-size: 18px; font-weight: 700; margin-bottom: 20px; text-align: center;">
                                📊 Resultados
                            </h3>
                            
                            <div style="height: 400px; margin-bottom: 30px;">
                                <canvas id="dynamicChart"></canvas>
                            </div>
                            
                            <div class="stats-table-container">
                                <h4 id="dynamicResultTableTitle" class="stats-table-title">Resumen de Datos</h4>
                                <div class="stats-table-wrapper">
                                    <table class="stats-table">
                                        <thead id="dynamicResultTableHead">
                                        </thead>
                                        <tbody id="dynamicResultTableBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: center; gap: 10px; margin-top: 25px;">
                                <button id="btnNewChart" class="btn-primary">🔄 Nueva Gráfica</button>
                                <button id="btnDownloadChart" class="btn-success">💾 Descargar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Manual de Usuario -->
    <div id="userManualModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">📖 Manual de Usuario - Sistema de Reportes</h2>
                <button class="modal-close-btn" id="closeManualBtn">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Manual Content with Accordion -->
                <div class="manual-section">
                    <div class="manual-item">
                        <button class="manual-item-header" data-section="1">
                            <span class="manual-item-icon">▶</span>
                            <span class="manual-item-title">1. Descripción General del Sistema</span>
                        </button>
                        <div class="manual-item-content" id="section-1" style="display: none;">
                            <p>El sistema de reportes permite cargar y analizar archivos Excel con datos de inscritos en programas de formación. Dispone de tres módulos principales:</p>
                            <ul>
                                <li><strong>Reporte General:</strong> Análisis comparativo de cupos vs inscritos por ficha</li>
                                <li><strong>Reporte Individual por Ficha:</strong> Estadísticas detalladas de aprendices por ficha</li>
                                <li><strong>Consolidador de Informes:</strong> Unificación de múltiples archivos con descargas Excel/PDF</li>
                            </ul>
                        </div>
                    </div>

                    <div class="manual-item">
                        <button class="manual-item-header" data-section="2">
                            <span class="manual-item-icon">▶</span>
                            <span class="manual-item-title">2. Reporte General de Inscripciones</span>
                        </button>
                        <div class="manual-item-content" id="section-2" style="display: none;">
                            <p><strong>Funcionalidad:</strong> Carga un archivo Excel con datos generales de fichas y obtén análisis detallados.</p>
                            <h4>Pasos:</h4>
                            <ol>
                                <li>Selecciona la pestaña "Reporte General"</li>
                                <li>Arrastra un archivo Excel o haz clic para seleccionar</li>
                                <li>El sistema procesará automáticamente los datos</li>
                                <li>Visualiza gráficas de barras, líneas o circulares</li>
                                <li>Cambia el tipo de gráfica con el selector desplegable</li>
                            </ol>
                            <p><strong>Archivo requerido:</strong> Excel con columnas: COD_FICHA, PROGRAMA, CUPO, INSCRITOS_1, INSCRITOS_2</p>
                        </div>
                    </div>

                    <div class="manual-item">
                        <button class="manual-item-header" data-section="3">
                            <span class="manual-item-icon">▶</span>
                            <span class="manual-item-title">3. Reporte Individual por Ficha</span>
                        </button>
                        <div class="manual-item-content" id="section-3" style="display: none;">
                            <p><strong>Funcionalidad:</strong> Carga uno o múltiples archivos para consolidar aprendices y ver estadísticas detalladas.</p>
                            <h4>Características:</h4>
                            <ul>
                                <li>Permite carga de múltiples archivos simultáneamente</li>
                                <li>Consolida datos de diferentes fichas</li>
                                <li>Selector de gráficas: Barras, Líneas, Donut, Circular</li>
                                <li>Visualiza tabla de totales por estado</li>
                                <li>Detalle completo de aprendices con sus estados</li>
                            </ul>
                            <p><strong>Archivo requerido:</strong> Excel con columnas: IDENTIFICACIÓN, NOMBRE, ESTADO</p>
                        </div>
                    </div>

                    <div class="manual-item">
                        <button class="manual-item-header" data-section="4">
                            <span class="manual-item-icon">▶</span>
                            <span class="manual-item-title">4. Consolidar Informes Excel</span>
                        </button>
                        <div class="manual-item-content" id="section-4" style="display: none;">
                            <p><strong>Funcionalidad:</strong> Módulo exclusivo para unir múltiples archivos y generar reportes descargables.</p>
                            <h4>Pasos:</h4>
                            <ol>
                                <li>Selecciona la pestaña "Consolidar informes Excel"</li>
                                <li>Carga múltiples archivos Excel</li>
                                <li>Haz clic en "Consolidar archivos"</li>
                                <li>Revisa los resultados: tabla de estados + listado de fichas</li>
                                <li>Descarga en Excel o PDF usando los botones de descargas</li>
                            </ol>
                            <p><strong>Salida:</strong> Archivo con consolidación de todas las fichas, metadata completa y distribución de estados.</p>
                        </div>
                    </div>

                    <div class="manual-item">
                        <button class="manual-item-header" data-section="5">
                            <span class="manual-item-icon">▶</span>
                            <span class="manual-item-title">5. Gestión de Archivos</span>
                        </button>
                        <div class="manual-item-content" id="section-5" style="display: none;">
                            <h4>Cargar Archivos:</h4>
                            <ul>
                                <li><strong>Opción 1:</strong> Arrastra el archivo directamente a la zona de carga</li>
                                <li><strong>Opción 2:</strong> Haz clic en la zona para abrir el selector de archivos</li>
                                <li><strong>Límite:</strong> 10MB por archivo, formatos .xls y .xlsx</li>
                            </ul>
                            <h4>Manejo de Múltiples Archivos:</h4>
                            <ul>
                                <li>Se muestra lista de archivos cargados</li>
                                <li>Puedes remover archivos individuales con el botón "Remover"</li>
                                <li>Opción "Limpiar todo" elimina todos los archivos</li>
                            </ul>
                        </div>
                    </div>

                    <div class="manual-item">
                        <button class="manual-item-header" data-section="6">
                            <span class="manual-item-icon">▶</span>
                            <span class="manual-item-title">6. Visualización de Gráficas</span>
                        </button>
                        <div class="manual-item-content" id="section-6" style="display: none;">
                            <h4>Tipos de Gráficas Disponibles:</h4>
                            <ul>
                                <li><strong>Barras (📊):</strong> Comparación de valores con ejes X-Y</li>
                                <li><strong>Líneas (📈):</strong> Tendencia de datos conectados</li>
                                <li><strong>Donut (🍩):</strong> Proporción circular con agujero central</li>
                                <li><strong>Circular (🥧):</strong> Pastel tradicional sin agujero</li>
                            </ul>
                            <p><strong>Interacción:</strong> Pasa el mouse sobre la gráfica para ver valores detallados.</p>
                        </div>
                    </div>

                    <div class="manual-item">
                        <button class="manual-item-header" data-section="7">
                            <span class="manual-item-icon">▶</span>
                            <span class="manual-item-title">7. Descargas y Exportación</span>
                        </button>
                        <div class="manual-item-content" id="section-7" style="display: none;">
                            <h4>Módulo: Consolidador de Informes</h4>
                            <p>Después de consolidar archivos, están disponibles dos opciones de descarga:</p>
                            <ul>
                                <li><strong>Excel (📊):</strong> Descarga un archivo .xlsx con:
                                    <ul>
                                        <li>Metadata del consolidado</li>
                                        <li>Tabla de estados globales</li>
                                        <li>Detalle de cada ficha</li>
                                        <li>Estilos y formatos profesionales</li>
                                    </ul>
                                </li>
                                <li><strong>PDF (📄):</strong> Documento PDF imprimible con:
                                    <ul>
                                        <li>Encabezado institucional</li>
                                        <li>Tablas formateadas</li>
                                        <li>Listo para presentaciones</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="manual-item">
                        <button class="manual-item-header" data-section="8">
                            <span class="manual-item-icon">▶</span>
                            <span class="manual-item-title">8. Solución de Problemas</span>
                        </button>
                        <div class="manual-item-content" id="section-8" style="display: none;">
                            <h4>Errores Comunes:</h4>
                            <ul>
                                <li><strong>"El archivo debe ser formato Excel":</strong> Verifica que sea .xls o .xlsx</li>
                                <li><strong>"El archivo pesa más de 10MB":</strong> Divide el archivo en partes más pequeñas</li>
                                <li><strong>"Columna no encontrada":</strong> Asegúrate de que el Excel tiene las columnas requeridas</li>
                                <li><strong>"Error al procesar":</strong> Recarga la página e intenta nuevamente</li>
                            </ul>
                            <h4>Contacto:</h4>
                            <p>Para soporte técnico adicional, contacta al administrador del sistema.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
@vite(['resources/css/dashboard.css'])
@vite(['resources/js/admin/dashboard-stats.js'])
@vite(['resources/js/admin/dynamic-chart-wizard.js'])

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userManualBtn = document.getElementById('userManualBtn');
    const userManualModal = document.getElementById('userManualModal');
    const closeManualBtn = document.getElementById('closeManualBtn');
    const manualItemHeaders = document.querySelectorAll('.manual-item-header');

    // Abrir modal
    userManualBtn.addEventListener('click', function(e) {
        e.preventDefault();
        userManualModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    // Cerrar modal con botón close
    closeManualBtn.addEventListener('click', function(e) {
        e.preventDefault();
        userManualModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    });

    // Cerrar modal al hacer click en overlay
    userManualModal.addEventListener('click', function(e) {
        if (e.target === userManualModal) {
            userManualModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });

    // Expandir/contraer secciones acordeón
    manualItemHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isExpanded = this.classList.contains('expanded');
            const contentId = this.getAttribute('data-section');
            const content = document.getElementById('section-' + contentId);

            if (isExpanded) {
                // Contraer
                this.classList.remove('expanded');
                content.style.display = 'none';
            } else {
                // Expandir
                this.classList.add('expanded');
                content.style.display = 'block';
                
                // Scroll suave a la sección
                setTimeout(() => {
                    content.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        });
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && userManualModal.classList.contains('active')) {
            userManualModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
});
</script>
@endsection
