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
                    <!-- Botón para formulario manual -->
                    <div style="margin-bottom: 30px; text-align: center;">
                        <button 
                            id="toggleManualFormBtn" 
                            type="button" 
                            class="stats-select" 
                            aria-label="Mostrar o ocultar formulario de ingreso manual de datos"
                            aria-expanded="false"
                            aria-controls="manualDataForm"
                            style="
                                padding: 10px 20px;
                                background: #39a900;
                                color: white;
                                border: 2px solid transparent;
                                border-radius: 6px;
                                cursor: pointer;
                                font-weight: 600;
                                font-size: 14px;
                                transition: all 0.2s ease;
                            "
                            onmouseover="this.style.background='#328a00'; this.style.outline='2px solid #39a900'; this.style.outlineOffset='2px';"
                            onmouseout="this.style.background='#39a900'; this.style.outline='none';"
                        >
                            ➕ Ingresar Datos Manualmente
                        </button>
                    </div>

                    <!-- Formulario manual para ingresar datos -->
                    <div 
                        id="manualDataForm" 
                        role="form"
                        aria-labelledby="manualFormTitle"
                        style="display: none; margin-bottom: 40px; padding: 25px; background: #f9f9f9; border-radius: 8px; border: 2px solid #39a900;">
                        
                        <h3 id="manualFormTitle" style="color: #333; font-size: 16px; font-weight: 700; margin-bottom: 20px;">
                            📋 Formulario de Ingreso Manual de Datos
                        </h3>

                        <!-- Mensaje de validación accesible -->
                        <div id="manualFormValidationMsg" role="alert" aria-live="polite" style="
                            display: none;
                            padding: 12px 15px;
                            margin-bottom: 20px;
                            border-radius: 4px;
                            font-size: 13px;
                            background: #f8d7da;
                            color: #721c24;
                            border: 1px solid #f5c6cb;
                        "></div>

                        <fieldset style="border: none; padding: 0; margin: 0; margin-bottom: 30px;">
                            <legend style="font-weight: 700; color: #FF4444; font-size: 11px; margin-bottom: 15px; display: block;">
                                Campos requeridos (<span style="color: #FF4444;">*</span>)
                            </legend>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <!-- Código de Ficha -->
                                <div>
                                    <label for="manualFichaCodigo" style="display: block; font-weight: 600; margin-bottom: 8px; color: #333; font-size: 13px;">
                                        Código de Ficha: <span style="color: #FF4444; font-weight: 700;">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="manualFichaCodigo" 
                                        placeholder="Ej: SOE-001"
                                        required
                                        aria-required="true"
                                        aria-describedby="codigoHelp"
                                        data-error="codigoError"
                                        style="
                                            width: 100%;
                                            padding: 10px;
                                            border: 2px solid #ddd;
                                            border-radius: 4px;
                                            font-size: 13px;
                                            box-sizing: border-box;
                                            transition: border-color 0.2s ease;
                                        "
                                        onfocus="this.style.borderColor='#39a900'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(57,169,0,0.1)';"
                                        onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none';"
                                    >
                                    <small id="codigoHelp" style="display: block; color: #666; font-size: 11px; margin-top: 4px;">Identifique únicamente la ficha de formación</small>
                                    <span id="codigoError" role="alert" style="display: none; color: #dc3545; font-size: 11px; margin-top: 4px;"></span>
                                </div>

                                <!-- Programa -->
                                <div>
                                    <label for="manualPrograma" style="display: block; font-weight: 600; margin-bottom: 8px; color: #333; font-size: 13px;">
                                        Programa de Formación: <span style="color: #FF4444; font-weight: 700;">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="manualPrograma" 
                                        placeholder="Ej: Desarrollo de Software"
                                        required
                                        aria-required="true"
                                        aria-describedby="programaHelp"
                                        data-error="programaError"
                                        style="
                                            width: 100%;
                                            padding: 10px;
                                            border: 2px solid #ddd;
                                            border-radius: 4px;
                                            font-size: 13px;
                                            box-sizing: border-box;
                                            transition: border-color 0.2s ease;
                                        "
                                        onfocus="this.style.borderColor='#39a900'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(57,169,0,0.1)';"
                                        onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none';"
                                    >
                                    <small id="programaHelp" style="display: block; color: #666; font-size: 11px; margin-top: 4px;">Nombre del programa de formación</small>
                                    <span id="programaError" role="alert" style="display: none; color: #dc3545; font-size: 11px; margin-top: 4px;"></span>
                                </div>

                                <!-- Total Aprendices -->
                                <div>
                                    <label for="manualTotalAprendices" style="display: block; font-weight: 600; margin-bottom: 8px; color: #333; font-size: 13px;">
                                        Total de Aprendices: <span style="color: #999; font-size: 11px;">(opcional)</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        id="manualTotalAprendices" 
                                        placeholder="Ej: 50" 
                                        min="0"
                                        aria-describedby="totalHelp"
                                        data-error="totalError"
                                        style="
                                            width: 100%;
                                            padding: 10px;
                                            border: 2px solid #ddd;
                                            border-radius: 4px;
                                            font-size: 13px;
                                            box-sizing: border-box;
                                            transition: border-color 0.2s ease;
                                        "
                                        onfocus="this.style.borderColor='#39a900'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(57,169,0,0.1)';"
                                        onblur="this.style.borderColor='#ddd'; this.style.boxShadow='none';"
                                    >
                                    <small id="totalHelp" style="display: block; color: #666; font-size: 11px; margin-top: 4px;">Cantidad total de aprendices (si se deja vacío, se sumará el total de estados)</small>
                                    <span id="totalError" role="alert" style="display: none; color: #dc3545; font-size: 11px; margin-top: 4px;"></span>
                                </div>

                                <div></div>
                            </div>
                        </fieldset>

                        <!-- Tabla de Estados Dinámicos -->
                        <fieldset style="border: none; padding: 0; margin: 0; margin-bottom: 20px;">
                            <legend style="font-weight: 700; color: #333; font-size: 14px; margin-bottom: 15px; display: block;">
                                Estados y Cantidades de Aprendices <span style="color: #FF4444; font-weight: 700;">*</span>
                            </legend>
                            
                            <table 
                                role="table"
                                aria-label="Tabla de Estados y Cantidades de Aprendices"
                                aria-describedby="tableDescription"
                                style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                                <caption id="tableDescription" style="font-size: 12px; color: #666; padding: 5px 0; text-align: left;">
                                    Ingrese el estado de los aprendices y la cantidad en cada fila. Puede agregar más estados con el botón "Agregar Estado"
                                </caption>
                                <thead>
                                    <tr style="background: #39a900; color: white;">
                                        <th style="padding: 10px; text-align: left; font-size: 12px; font-weight: 700;">
                                            Estado <span style="color: #FFD700;">*</span>
                                        </th>
                                        <th style="padding: 10px; text-align: left; font-size: 12px; font-weight: 700;">
                                            Total de Aprendices <span style="color: #FFD700;">*</span>
                                        </th>
                                        <th style="padding: 10px; text-align: center; font-size: 12px; font-weight: 700; width: 80px;">
                                            Acción
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="manualEstadosTableBody">
                                    <!-- Filas dinámicas se insertarán aquí -->
                                </tbody>
                            </table>

                            <button 
                                id="addEstadoBtn" 
                                type="button"
                                aria-label="Agregar nueva fila para estado de aprendices"
                                style="
                                    padding: 8px 15px;
                                    background: #007bff;
                                    color: white;
                                    border: 2px solid transparent;
                                    border-radius: 4px;
                                    cursor: pointer;
                                    font-weight: 600;
                                    font-size: 12px;
                                    margin-bottom: 20px;
                                    transition: all 0.2s ease;
                                "
                                onmouseover="this.style.background='#0056b3'; this.style.outline='2px solid #007bff'; this.style.outlineOffset='2px';"
                                onmouseout="this.style.background='#007bff'; this.style.outline='none';"
                            >
                                + Agregar Estado
                            </button>
                        </fieldset>

                        <!-- Botones de Acción -->
                        <div style="display: flex; gap: 10px; justify-content: center;">
                            <button 
                                id="generateManualChartsBtn" 
                                type="button"
                                aria-label="Generar gráficas y estadísticas con los datos ingresados"
                                style="
                                    padding: 12px 24px;
                                    background: #39a900;
                                    color: white;
                                    border: 2px solid transparent;
                                    border-radius: 6px;
                                    cursor: pointer;
                                    font-weight: 700;
                                    font-size: 14px;
                                    transition: all 0.2s ease;
                                "
                                onmouseover="this.style.background='#328a00'; this.style.outline='2px solid #39a900'; this.style.outlineOffset='2px';"
                                onmouseout="this.style.background='#39a900'; this.style.outline='none';"
                            >
                                ✓ Generar Gráficas y Estadísticas
                            </button>
                            <button 
                                id="cancelManualFormBtn" 
                                type="button"
                                aria-label="Cancelar y cerrar el formulario de ingreso manual"
                                style="
                                    padding: 12px 24px;
                                    background: #999;
                                    color: white;
                                    border: 2px solid transparent;
                                    border-radius: 6px;
                                    cursor: pointer;
                                    font-weight: 600;
                                    font-size: 14px;
                                    transition: all 0.2s ease;
                                "
                                onmouseover="this.style.background='#777'; this.style.outline='2px solid #999'; this.style.outlineOffset='2px';"
                                onmouseout="this.style.background='#999'; this.style.outline='none';"
                            >
                                ✕ Cancelar
                            </button>
                        </div>
                    </div>

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
                                <li><strong>Consolidador de Fichas:</strong> Unificación de múltiples archivos con descargas Excel/PDF</li>
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
                            <span class="manual-item-title">4. Consolidador de Fichas Excel</span>
                        </button>
                        <div class="manual-item-content" id="section-4" style="display: none;">
                            <p><strong>Funcionalidad:</strong> Módulo exclusivo para unir múltiples archivos y generar reportes descargables.</p>
                            <h4>Pasos:</h4>
                            <ol>
                                <li>Selecciona la pestaña "Consolidador de Fichas Excel"</li>
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
                            <h4>Módulo: Consolidador de Fichas</h4>
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

@section('styles')
<style>
/* Botón Manual de Usuario */
.user-manual-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #39A900 0%, #2d8400 100%);
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(57, 169, 0, 0.3);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 998;
}

.user-manual-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(57, 169, 0, 0.4);
}

.user-manual-btn:active {
    transform: scale(0.95);
}

.manual-icon {
    width: 28px;
    height: 28px;
    stroke-width: 2;
}

/* Modal Overlay */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    overflow-y: auto;
    padding: 20px;
}

.modal-overlay.active {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 40px;
}

.modal-content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    max-width: 800px;
    width: 100%;
    max-height: 85vh;
    overflow-y: auto;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 2px solid #f0f0f0;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    position: sticky;
    top: 0;
    z-index: 1000;
    border-radius: 12px 12px 0 0;
}

.modal-title {
    font-size: 22px;
    font-weight: 700;
    margin: 0;
    color: #333;
}

.modal-close-btn {
    background: none;
    border: none;
    font-size: 32px;
    cursor: pointer;
    color: #999;
    padding: 0;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.modal-close-btn:hover {
    background: #f0f0f0;
    color: #333;
}

.modal-body {
    padding: 24px;
}

/* Manual Sections */
.manual-section {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.manual-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    background: #fafafa;
    transition: all 0.2s ease;
}

.manual-item:hover {
    border-color: #39A900;
    box-shadow: 0 2px 8px rgba(57, 169, 0, 0.1);
}

.manual-item-header {
    width: 100%;
    padding: 16px;
    background: white;
    border: none;
    text-align: left;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s ease;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.manual-item-header:hover {
    background: #f8f9fa;
    color: #39A900;
}

.manual-item-header.expanded {
    background: linear-gradient(135deg, #f0fdf4 0%, #f8f9fa 100%);
    color: #2d8400;
    border-bottom: 2px solid #39A900;
}

.manual-item-icon {
    display: inline-block;
    transition: transform 0.3s ease;
    font-size: 12px;
    color: #39A900;
}

.manual-item-header.expanded .manual-item-icon {
    transform: rotate(90deg);
}

.manual-item-title {
    flex: 1;
}

.manual-item-content {
    padding: 16px;
    background: white;
    border-top: 1px solid #e0e0e0;
    animation: slideOpen 0.3s ease;
}

@keyframes slideOpen {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 1000px;
    }
}

.manual-item-content p {
    margin: 0 0 12px 0;
    line-height: 1.6;
    color: #555;
}

.manual-item-content h4 {
    margin: 16px 0 8px 0;
    color: #39A900;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.manual-item-content ul,
.manual-item-content ol {
    margin: 8px 0;
    padding-left: 24px;
}

.manual-item-content li {
    margin: 6px 0;
    line-height: 1.5;
    color: #555;
}

.manual-item-content strong {
    color: #2d8400;
}

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
    max-height: 450px;
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
