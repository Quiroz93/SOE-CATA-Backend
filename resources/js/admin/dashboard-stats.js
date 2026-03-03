/**
 * Dashboard Stats Module - Real-time Excel Analysis
 * Handles drag & drop, file upload, and chart rendering for program statistics
 */

import Chart from 'chart.js/auto';

// Estado global del módulo
const state = {
    charts: [],        // Array para múltiples gráficas
    demandChart: null,
    individualChart: null,
    currentData: null,
    activeReportKind: 'general_inscripciones',
    uploadedFiles: []  // Almacenar archivos cargados para consolidación
};

// Elementos del DOM
const dropZone = document.getElementById('dropZone');
const inputFile = document.getElementById('excelFile');
const statusText = document.getElementById('statusText');
const statsResults = document.getElementById('statsResults');
const statsMetadata = document.getElementById('statsMetadata');
const statsTableBody = document.getElementById('statsTableBody');
const comparisonTable = document.getElementById('comparisonTable');
const reportType = document.getElementById('reportType');
const programChartContainer = document.getElementById('programChartContainer');
const statsLiveTitle = document.getElementById('statsLiveTitle');
const statsLiveSubtitle = document.getElementById('statsLiveSubtitle');
const chartTypeControl = document.getElementById('chartTypeControl');
const reportTabs = document.querySelectorAll('.stats-tab');
const statsResultsGeneral = document.getElementById('statsResultsGeneral');
const statsResultsIndividual = document.getElementById('statsResultsIndividual');
const individualStateChart = document.getElementById('individualStateChart');
const individualStatesTableBody = document.getElementById('individualStatesTableBody');
const individualTableBody = document.getElementById('individualTableBody');

// Configuración
const uploadUrl = '/admin/dashboard/estadisticas/upload';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

/**
 * Inicializar eventos
 */
function init() {
    if (!dropZone || !inputFile) return;

    setActiveReportKind('general_inscripciones');

    // Click en zona de drop abre selector de archivo
    dropZone.addEventListener('click', () => inputFile.click());

    // Cambio en input de archivo
    inputFile.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        if (file) uploadFile(file);
    });

    // Drag & Drop
    ['dragenter', 'dragover'].forEach(evt => {
        dropZone.addEventListener(evt, (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
        dropZone.addEventListener(evt, (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        });
    });

    dropZone.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files?.[0];
        if (file) uploadFile(file);
    });

    // Cambio de tipo de reporte
    reportType?.addEventListener('change', () => {
        if (state.currentData && state.activeReportKind === 'general_inscripciones') {
            renderChart(state.currentData.tabla || []);
        }
    });

    reportTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const kind = tab.dataset.reportKind || 'general_inscripciones';
            setActiveReportKind(kind);
            resetView();
        });
    });
}

/**
 * Subir y procesar archivo(s)
 */
async function uploadFile(file) {
    // Si es reporte individual y el input permite múltiples, usar lógica de consolidación
    if (state.activeReportKind === 'individual_ficha' && inputFile.multiple) {
        return uploadMultipleFiles([file]);
    }

    // Validar extensión
    const validExtensions = ['.xls', '.xlsx'];
    const fileExtension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
    
    if (!validExtensions.includes(fileExtension)) {
        showStatus('Error: El archivo debe ser formato Excel (.xls o .xlsx)', 'error');
        return;
    }

    // Validar tamaño (10MB)
    if (file.size > 10 * 1024 * 1024) {
        showStatus('Error: El archivo no puede pesar más de 10MB', 'error');
        return;
    }

    showStatus('Procesando archivo...', 'loading');
    statsResults.style.display = 'none';

    const formData = new FormData();
    formData.append('file', file);
    formData.append('report_kind', state.activeReportKind);

    try {
        const response = await fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Error al procesar el archivo');
        }

        state.currentData = data;
        const successMessage = state.activeReportKind === 'individual_ficha'
            ? `Archivo procesado correctamente. Total de aprendices: ${data.metadata?.totalAprendices ?? (data.tabla || []).length}`
            : `Archivo procesado correctamente. Total de fichas: ${data.metadata?.totalFichas ?? data.totalRegistros}`;

        showStatus(successMessage, 'success');
        renderMetadata(data.metadata, state.activeReportKind);
        renderResults(data);

    } catch (error) {
        showStatus(`Error: ${error.message}`, 'error');
        console.error('Error al procesar archivo:', error);
    }
}

/**
 * Procesar múltiples archivos para consolidación
 */
async function uploadMultipleFiles(files) {
    // Validar extensiones
    const validExtensions = ['.xls', '.xlsx'];
    let totalSize = 0;
    
    for (const file of files) {
        const fileExtension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
        if (!validExtensions.includes(fileExtension)) {
            showStatus(`Error: El archivo ${file.name} no es formato Excel válido`, 'error');
            return;
        }
        
        if (file.size > 10 * 1024 * 1024) {
            showStatus(`Error: El archivo ${file.name} pesa más de 10MB`, 'error');
            return;
        }
        
        totalSize += file.size;
    }

    // Agregar archivos al estado
    state.uploadedFiles.push(...files);

    // Mostrar lista de archivos cargados
    updateFilesList();
    
    showStatus(`${state.uploadedFiles.length} archivo(s) cargado(s). Selecciona más o haz clic en "Consolidar" para procesar.`, 'info');
}

/**
 * Actualizar lista de archivos cargados
 */
function updateFilesList() {
    let filesList = document.getElementById('uploadedFilesList');
    
    if (!filesList) {
        // Crear lista si no existe
        filesList = document.createElement('div');
        filesList.id = 'uploadedFilesList';
        filesList.style.marginTop = '20px';
        filesList.style.padding = '15px';
        filesList.style.backgroundColor = '#f0f9ff';
        filesList.style.borderRadius = '8px';
        filesList.style.borderLeft = '4px solid #39A900';
        
        const insertPoint = document.querySelector('.stats-status');
        if (insertPoint && insertPoint.parentNode) {
            insertPoint.parentNode.insertBefore(filesList, insertPoint.nextSibling);
        }
    }

    if (state.uploadedFiles.length === 0) {
        filesList.style.display = 'none';
        return;
    }

    filesList.style.display = 'block';
    filesList.innerHTML = `
        <div style="margin-bottom: 15px;">
            <strong>Archivos cargados (${state.uploadedFiles.length}):</strong>
            <ul style="list-style: none; padding: 10px 0; margin: 0;">
                ${state.uploadedFiles.map((file, idx) => `
                    <li style="padding: 5px 0; display: flex; justify-content: space-between; align-items: center;">
                        <span>📄 ${escapeHtml(file.name)}</span>
                        <button type="button" data-file-index="${idx}" class="remove-file-btn" style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 12px;">Remover</button>
                    </li>
                `).join('')}
            </ul>
        </div>
        <div style="display: flex; gap: 10px;">
            <button id="consolidateBtn" type="button" style="flex: 1; padding: 10px; background: #39A900; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                ✓ Consolidar ${state.uploadedFiles.length} archivo(s)
            </button>
            <button id="clearFilesBtn" type="button" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer;">
                Limpiar todo
            </button>
        </div>
    `;

    // Agregar event listeners
    document.querySelectorAll('.remove-file-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const idx = parseInt(e.target.dataset.fileIndex);
            state.uploadedFiles.splice(idx, 1);
            updateFilesList();
        });
    });

    document.getElementById('consolidateBtn')?.addEventListener('click', consolidateFiles);
    document.getElementById('clearFilesBtn')?.addEventListener('click', () => {
        state.uploadedFiles = [];
        updateFilesList();
        resetView();
    });
}

/**
 * Consolidar archivos cargados
 */
async function consolidateFiles() {
    if (state.uploadedFiles.length === 0) {
        showStatus('Error: No hay archivos cargados para consolidar', 'error');
        return;
    }

    showStatus('Consolidando archivos...', 'loading');
    statsResults.style.display = 'none';

    const formData = new FormData();
    
    // Agregar todos los archivos
    state.uploadedFiles.forEach(file => {
        formData.append('files[]', file);
    });
    
    formData.append('report_kind', 'individual_ficha_consolidado');

    try {
        const response = await fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Error al consolidar archivos');
        }

        state.currentData = data;
        
        const successMessage = `Consolidación exitosa: ${data.totales?.fichas ?? 0} fichas, ${data.totales?.aprendices ?? 0} aprendices`;
        showStatus(successMessage, 'success');
        
        renderMetadataConsolidado(data);
        renderConsolidatedResults(data);
        renderDownloadButtons();
        
        // Limpiar archivos después de consolidar exitosamente
        state.uploadedFiles = [];
        updateFilesList();

    } catch (error) {
        showStatus(`Error: ${error.message}`, 'error');
        console.error('Error al consolidar archivos:', error);
    }
}

/**
 * Renderizar botones de descarga
 */
function renderDownloadButtons() {
    let downloadContainer = document.getElementById('downloadButtonsContainer');
    
    if (!downloadContainer) {
        downloadContainer = document.createElement('div');
        downloadContainer.id = 'downloadButtonsContainer';
        downloadContainer.style.marginTop = '20px';
        downloadContainer.style.padding = '15px';
        downloadContainer.style.backgroundColor = '#f0f9ff';
        downloadContainer.style.borderRadius = '8px';
        downloadContainer.style.borderLeft = '4px solid #1976d2';
        downloadContainer.style.display = 'flex';
        downloadContainer.style.gap = '10px';
        downloadContainer.style.flexWrap = 'wrap';
        
        const insertPoint = document.querySelector('.stats-results');
        if (insertPoint) {
            insertPoint.parentNode.insertBefore(downloadContainer, insertPoint.nextSibling);
        }
    }

    downloadContainer.innerHTML = `
        <div style="flex: 1; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <span style="font-weight: 600; color: #333;">Descargar consolidado:</span>
            <button type="button" id="downloadExcelBtn" style="background: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                📊 Excel
            </button>
            <button type="button" id="downloadPDFBtn" style="background: #1976d2; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                📄 PDF
            </button>
        </div>
    `;

    document.getElementById('downloadExcelBtn')?.addEventListener('click', downloadExcel);
    document.getElementById('downloadPDFBtn')?.addEventListener('click', downloadPDF);
}

/**
 * Descargar archivo Excel
 */
function downloadExcel() {
    const link = document.createElement('a');
    link.href = '/admin/dashboard/estadisticas/download-excel';
    link.click();
}

/**
 * Descargar archivo PDF
 */
function downloadPDF() {
    const link = document.createElement('a');
    link.href = '/admin/dashboard/estadisticas/download-pdf';
    link.setAttribute('target', '_blank');
    link.click();
}

/**
 * Mostrar mensaje de estado
 */
function showStatus(message, type) {
    statusText.textContent = message;
    statusText.className = `stats-status ${type}`;
}

/**Mostrar metadata del análisis
 */
function renderMetadata(metadata, reportKind) {
    if (!statsMetadata || !metadata) return;

    const metaLabel1 = document.getElementById('metaLabel1');
    const metaLabel2 = document.getElementById('metaLabel2');
    const metaLabel3 = document.getElementById('metaLabel3');
    const metaLabel4 = document.getElementById('metaLabel4');
    const metaValue1 = document.getElementById('metaValue1');
    const metaValue2 = document.getElementById('metaValue2');
    const metaValue3 = document.getElementById('metaValue3');
    const metaValue4 = document.getElementById('metaValue4');

    if (!metaLabel1 || !metaLabel2 || !metaLabel3 || !metaLabel4 || !metaValue1 || !metaValue2 || !metaValue3 || !metaValue4) {
        return;
    }

    if (reportKind === 'individual_ficha') {
        metaLabel1.textContent = 'Código Ficha:';
        metaLabel2.textContent = 'Programa de Formación:';
        metaLabel3.textContent = 'Total Aprendices:';
        metaLabel4.textContent = 'Estados Detectados:';

        metaValue1.textContent = metadata.ficha || '-';
        metaValue2.textContent = metadata.programa || '-';
        metaValue3.textContent = metadata.totalAprendices || 0;
        metaValue4.textContent = metadata.totalEstados || 0;
    } else {
        metaLabel1.textContent = 'Total Fichas:';
        metaLabel2.textContent = 'Total Inscritos:';
        metaLabel3.textContent = 'Total Cupos:';
        metaLabel4.textContent = 'Ocupación Promedio:';

        metaValue1.textContent = metadata.totalFichas || 0;
        metaValue2.textContent = metadata.totalInscritos || 0;
        metaValue3.textContent = metadata.totalCupos || 0;
        metaValue4.textContent = `${metadata.ocupacionPromedio || 0}%`;
    }

    statsMetadata.style.display = 'block';
}

/**
 * Dividir texto largo en dos líneas para tooltips
 */
function splitLongText(text, maxLength = 50) {
    if (text.length <= maxLength) {
        return [text];
    }
    
    // Buscar punto medio para dividir en palabras
    const midPoint = Math.floor(text.length / 2);
    let splitIndex = text.lastIndexOf(' ', midPoint);
    
    // Si no hay espacio cerca del medio, buscar hacia adelante
    if (splitIndex === -1 || splitIndex < midPoint - 20) {
        splitIndex = text.indexOf(' ', midPoint);
    }
    
    // Si aún no hay espacio, cortar en el máximo permitido
    if (splitIndex === -1) {
        splitIndex = maxLength;
    }
    
    const firstLine = text.substring(0, splitIndex).trim();
    const secondLine = text.substring(splitIndex).trim();
    
    return [firstLine, secondLine];
}

function setActiveReportKind(reportKind) {
    state.activeReportKind = reportKind;
    state.uploadedFiles = []; // Limpiar archivos al cambiar de pestaña

    reportTabs.forEach((tab) => {
        tab.classList.toggle('active', tab.dataset.reportKind === reportKind);
    });

    if (reportKind === 'individual_ficha') {
        if (statsLiveTitle) statsLiveTitle.textContent = 'Consolidar Reportes Individuales por Ficha';
        if (statsLiveSubtitle) statsLiveSubtitle.textContent = 'Carga múltiples archivos para consolidar datos de aprendices por ficha. Estructura esperada: Identificación, Nombre, Estado';
        if (chartTypeControl) chartTypeControl.style.display = 'none';
        
        // Permitir múltiples archivos
        inputFile.multiple = true;
        
        // Mostrar zona de carga múltiple
        updateDropZoneForMultiple();
    } else {
        if (statsLiveTitle) statsLiveTitle.textContent = 'Estadísticas en Tiempo Real por COD_FICHA';
        if (statsLiveSubtitle) statsLiveSubtitle.textContent = 'Compara por ficha el CUPO contra INSCRITOS PRIMERA y SEGUNDA OPCIÓN, con porcentaje de demanda y sobrecupo';
        if (chartTypeControl) chartTypeControl.style.display = 'block';
        
        // Un único archivo
        inputFile.multiple = false;
        
        // Restaurar zona de carga simple
        updateDropZoneForSingle();
    }
}

/**
 * Actualizar zona de drop para carga múltiple
 */
function updateDropZoneForMultiple() {
    const title = dropZone?.querySelector('.stats-upload-title');
    const text = dropZone?.querySelector('.stats-upload-text');
    
    if (title) title.textContent = 'Arrastra aquí tus archivos Excel (múltiples)';
    if (text) text.textContent = 'o haz clic para seleccionar varios archivos';
}

/**
 * Actualizar zona de drop para carga simple
 */
function updateDropZoneForSingle() {
    const title = dropZone?.querySelector('.stats-upload-title');
    const text = dropZone?.querySelector('.stats-upload-text');
    
    if (title) title.textContent = 'Arrastra aquí tu archivo Excel';
    if (text) text.textContent = 'o haz clic para seleccionar';
}

function destroyCharts() {
    state.charts.forEach(chart => chart.destroy());
    state.charts = [];

    if (state.demandChart) {
        state.demandChart.destroy();
        state.demandChart = null;
    }

    if (state.individualChart) {
        state.individualChart.destroy();
        state.individualChart = null;
    }
}

function resetView() {
    destroyCharts();
    state.currentData = null;

    if (statsResults) statsResults.style.display = 'none';
    if (statsMetadata) statsMetadata.style.display = 'none';
    if (statusText) showStatus('', '');

    if (statsResultsGeneral) statsResultsGeneral.style.display = 'none';
    if (statsResultsIndividual) statsResultsIndividual.style.display = 'none';

    if (programChartContainer) {
        programChartContainer.innerHTML = '';
    }

    if (individualStatesTableBody) {
        individualStatesTableBody.innerHTML = '';
    }

    if (individualTableBody) {
        individualTableBody.innerHTML = '';
    }
}

function renderIndividualResults(data) {
    const rows = data.tabla || [];
    const estadoTotales = data.estado_totales || {};

    if (statsResultsGeneral) statsResultsGeneral.style.display = 'none';
    if (statsResultsIndividual) statsResultsIndividual.style.display = 'block';

    renderIndividualChart(estadoTotales);
    renderIndividualStatesTable(estadoTotales);
    renderIndividualTable(rows);
}

function renderIndividualChart(estadoTotales) {
    const ctx = individualStateChart?.getContext('2d');
    if (!ctx) return;

    if (state.individualChart) {
        state.individualChart.destroy();
    }

    const labels = Object.keys(estadoTotales);
    const values = Object.values(estadoTotales).map(value => Number(value || 0));

    state.individualChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Aprendices por Estado',
                    data: values,
                    backgroundColor: 'rgba(57, 169, 0, 0.80)',
                    borderColor: '#39A900',
                    borderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Distribución de Aprendices por Estado',
                    font: { size: 16, weight: 'bold' }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad de aprendices'
                    }
                }
            }
        }
    });
}

function renderIndividualStatesTable(estadoTotales) {
    if (!individualStatesTableBody) return;

    individualStatesTableBody.innerHTML = Object.entries(estadoTotales)
        .map(([estado, total]) => `
            <tr>
                <td style="font-weight: 500;">${escapeHtml(String(estado))}</td>
                <td><strong>${Number(total || 0)}</strong></td>
            </tr>
        `)
        .join('');
}

function renderIndividualTable(rows) {
    if (!individualTableBody) return;

    individualTableBody.innerHTML = rows.map((row) => `
        <tr>
            <td style="font-weight: 600;">${escapeHtml(String(row.identificacion || ''))}</td>
            <td>${escapeHtml(String(row.nombre || ''))}</td>
            <td>${escapeHtml(String(row.estado || 'Sin estado'))}</td>
        </tr>
    `).join('');
}

/**
 * Renderizar metadatos consolidados (múltiples fichas)
 */
function renderMetadataConsolidado(data) {
    if (!statsMetadata || !data.totales) return;

    const metaLabel1 = document.getElementById('metaLabel1');
    const metaLabel2 = document.getElementById('metaLabel2');
    const metaLabel3 = document.getElementById('metaLabel3');
    const metaLabel4 = document.getElementById('metaLabel4');
    const metaValue1 = document.getElementById('metaValue1');
    const metaValue2 = document.getElementById('metaValue2');
    const metaValue3 = document.getElementById('metaValue3');
    const metaValue4 = document.getElementById('metaValue4');

    if (!metaLabel1 || !metaLabel2 || !metaLabel3 || !metaLabel4 || !metaValue1 || !metaValue2 || !metaValue3 || !metaValue4) {
        return;
    }

    metaLabel1.textContent = 'Total Fichas:';
    metaLabel2.textContent = 'Total Aprendices:';
    metaLabel3.textContent = 'Estados Detectados:';
    metaLabel4.textContent = 'Última Carga:';

    metaValue1.textContent = data.totales.fichas || 0;
    metaValue2.textContent = data.totales.aprendices || 0;
    metaValue3.textContent = data.totales.estados || 0;
    metaValue4.textContent = data.timestamp ? new Date(data.timestamp).toLocaleDateString('es-CO', { hour: '2-digit', minute: '2-digit' }) : '-';

    statsMetadata.style.display = 'block';
}

/**
 * Renderizar resultados consolidados (múltiples fichas)
 */
function renderConsolidatedResults(data) {
    if (statsResultsGeneral) statsResultsGeneral.style.display = 'none';
    if (statsResultsIndividual) statsResultsIndividual.style.display = 'block';

    statsResults.style.display = 'block';

    // Renderizar gráfica de estados consolidados
    renderConsolidatedStatesChart(data.estados_globales || {});

    // Renderizar tabla de totales por estado
    renderConsolidatedStatesTable(data.estados_globales || {});

    // Renderizar tabla de fichas con detalles
    renderFichasTable(data.fichas || {});
}

/**
 * Renderizar gráfica consolidada de estados
 */
function renderConsolidatedStatesChart(estadosGlobales) {
    const ctx = individualStateChart?.getContext('2d');
    if (!ctx) return;

    if (state.individualChart) {
        state.individualChart.destroy();
    }

    const labels = Object.keys(estadosGlobales);
    const values = Object.values(estadosGlobales).map(value => Number(value || 0));

    state.individualChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [
                {
                    label: 'Aprendices Consolidados',
                    data: values,
                    backgroundColor: [
                        'rgba(57, 169, 0, 0.8)',      // Verde SENA
                        'rgba(0, 123, 255, 0.8)',     // Azul
                        'rgba(255, 193, 7, 0.8)',     // Amarillo
                        'rgba(220, 53, 69, 0.8)',     // Rojo
                        'rgba(108, 117, 125, 0.8)',   // Gris
                        'rgba(0, 200, 83, 0.8)',      // Verde esmeralda
                        'rgba(156, 39, 176, 0.8)',    // Púrpura
                        'rgba(255, 152, 0, 0.8)',     // Naranja
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Distribución Consolidada de Aprendices por Estado',
                    font: { size: 16, weight: 'bold' }
                },
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.parsed;
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

/**
 * Renderizar tabla de totales consolidados por estado
 */
function renderConsolidatedStatesTable(estadosGlobales) {
    if (!individualStatesTableBody) return;

    const sortedStates = Object.entries(estadosGlobales)
        .sort((a, b) => Number(b[1]) - Number(a[1]));

    individualStatesTableBody.innerHTML = sortedStates
        .map(([estado, total]) => `
            <tr>
                <td style="font-weight: 500;">${escapeHtml(String(estado))}</td>
                <td><strong>${Number(total || 0)}</strong></td>
            </tr>
        `)
        .join('');
}

/**
 * Renderizar tabla de fichas con detalles - MEJORADA
 */
function renderFichasTable(fichasData) {
    if (!individualTableBody) return;

    // Convertir a array si es necesario y ordenar por código de ficha
    const fichasArray = Array.isArray(fichasData) 
        ? fichasData 
        : Object.entries(fichasData).map(([code, data]) => ({...data, ficha: code}));
    
    const fichasOrdenadas = fichasArray.sort((a, b) => {
        const codeA = String(a.ficha || '').trim();
        const codeB = String(b.ficha || '').trim();
        return codeA.localeCompare(codeB);
    });

    if (fichasOrdenadas.length === 0) {
        individualTableBody.innerHTML = `
            <tr>
                <td colspan="3" style="text-align: center; padding: 20px; color: #999;">
                    No hay datos de fichas disponibles
                </td>
            </tr>
        `;
        return;
    }

    let html = '';

    fichasOrdenadas.forEach((fichaInfo, fichaIndex) => {
        const codigoFicha = String(fichaInfo.ficha || 'SIN CÓDIGO').trim();
        const programa = String(fichaInfo.programa || 'Sin programa').trim();
        const totalAprendices = fichaInfo.totalAprendices || 0;
        const aprendices = fichaInfo.aprendices || [];

        // Encabezado de ficha con mejor contraste
        const bgColor = fichaIndex % 2 === 0 ? '#e8f5e9' : '#f1f8e9';
        
        html += `
            <tr style="background: ${bgColor}; border-top: 3px solid #39A900; border-bottom: 2px solid #39A900;">
                <td colspan="3" style="padding: 15px; font-weight: 700; font-size: 14px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="background: #39A900; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">FICHA #${escapeHtml(codigoFicha)}</span>
                        <span style="color: #333;">📚 ${escapeHtml(programa)}</span>
                        <span style="color: #666; margin-left: auto; font-size: 12px;">👥 ${totalAprendices} aprendices</span>
                    </div>
                </td>
            </tr>
        `;

        // Aprendices de la ficha
        if (aprendices.length === 0) {
            html += `
                <tr style="border-left: 5px solid #39A900; background: #fafafa;">
                    <td colspan="3" style="padding: 12px; color: #999; font-style: italic;">
                        Sin registros de aprendices en esta ficha
                    </td>
                </tr>
            `;
        } else {
            aprendices.forEach((aprendiz, index) => {
                const isLastInFicha = index === aprendices.length - 1;
                const borderBottom = isLastInFicha ? '2px solid #39A900' : '1px solid #e0e0e0';
                
                html += `
                    <tr style="border-left: 5px solid #39A900; border-bottom: ${borderBottom}; background: ${bgColor};">
                        <td style="font-weight: 600; padding: 12px 12px 12px 30px; color: #1b5e20;">
                            ${escapeHtml(String(aprendiz.identificacion || ''))}
                        </td>
                        <td style="padding: 12px; color: #333;">
                            ${escapeHtml(String(aprendiz.nombre || ''))}
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; background: #e3f2fd; color: #0d47a1; font-size: 12px; font-weight: 500;">
                                ${escapeHtml(String(aprendiz.estado || 'Sin estado'))}
                            </span>
                        </td>
                    </tr>
                `;
            });
        }

        // Espaciador entre fichas
        if (fichaIndex < fichasOrdenadas.length - 1) {
            html += `<tr style="height: 8px; background: #fafafa;"><td colspan="3"></td></tr>`;
        }
    });

    individualTableBody.innerHTML = html;
}

/**
 * 
 * Renderizar resultados completos
 */
function renderResults(data) {
    statsResults.style.display = 'block';

    if (state.activeReportKind === 'individual_ficha' && !data.consolidado) {
        renderIndividualResults(data);
        return;
    }

    if (data.consolidado) {
        renderConsolidatedResults(data);
        return;
    }

    const rows = data.tabla || [];
    if (statsResultsGeneral) statsResultsGeneral.style.display = 'block';
    if (statsResultsIndividual) statsResultsIndividual.style.display = 'none';

    renderChart(rows);
    renderDemandChart(rows);
    renderMainTable(rows);
    renderComparison(rows);
}

/**
 * Renderizar gráfica principal
 */
function renderChart(rows) {
    // Destruir gráficas anteriores
    state.charts.forEach(chart => chart.destroy());
    state.charts = [];
    
    // Limpiar contenedor
    if (programChartContainer) {
        programChartContainer.innerHTML = '';
    }

    const labels = rows.map(r => r.ficha || 'Sin ficha');
    const cupos = rows.map(r => Number(r.cupos || 0));
    const inscritosPrimera = rows.map(r => Number(r.inscritos_primera || 0));
    const inscritosSegunda = rows.map(r => Number(r.inscritos_segunda || 0));

    const selectedType = reportType?.value || 'bar';
    let config;

    if (selectedType === 'pie') {
        // Dividir fichas en grupos de 15 máximo
        const groupSize = 15;
        const pieSource = rows
            .map(r => ({
                ficha: String(r.ficha || 'Sin ficha'),
                programa: String(r.programa || 'Sin programa'),
                primera: Number(r.inscritos_primera || 0),
                segunda: Number(r.inscritos_segunda || 0),
                cupo: Number(r.cupos || 0),
            }))
            .sort((a, b) => b.primera - a.primera);

        // Crear múltiples gráficas si hay más de 15 fichas
        const totalGroups = Math.ceil(pieSource.length / groupSize);
        
        for (let groupIndex = 0; groupIndex < totalGroups; groupIndex++) {
            const start = groupIndex * groupSize;
            const end = Math.min(start + groupSize, pieSource.length);
            const groupRows = pieSource.slice(start, end);

            // Crear contenedor para la gráfica
            const wrapper = document.createElement('div');
            wrapper.className = 'stats-pie-wrapper';
            wrapper.style.marginBottom = '30px';
            
            const title = document.createElement('h5');
            title.style.marginBottom = '15px';
            title.style.fontSize = '14px';
            title.style.fontWeight = '600';
            title.style.color = '#333';
            title.textContent = totalGroups > 1 ? `Gráfica ${groupIndex + 1} de ${totalGroups}` : '';
            
            const canvasWrapper = document.createElement('div');
            canvasWrapper.style.height = '360px';
            canvasWrapper.style.overflow = 'hidden';
            
            const canvas = document.createElement('canvas');
            canvas.id = `programChart-${groupIndex}`;
            canvas.style.width = '100% !important';
            canvas.style.height = '100% !important';
            canvas.style.maxWidth = '100%';
            canvas.style.display = 'block';
            
            canvasWrapper.appendChild(canvas);
            wrapper.appendChild(title);
            wrapper.appendChild(canvasWrapper);
            programChartContainer.appendChild(wrapper);

            // Crear gráfica para este grupo
            const ctx = canvas.getContext('2d');
            if (!ctx) continue;

            const pieLabels = groupRows.map(item => item.ficha);
            const pieValues = groupRows.map(item => item.primera);

            const sumValues = pieValues.reduce((acc, val) => acc + val, 0);
            const safeValues = sumValues > 0 ? pieValues : groupRows.map(item => item.cupo);

            const chartConfig = {
                type: 'pie',
                data: {
                    labels: pieLabels,
                    datasets: [
                        {
                            label: 'Inscritos Primera Opción',
                            data: safeValues,
                            backgroundColor: generateColors(pieLabels.length),
                            borderColor: '#ffffff',
                            borderWidth: 1,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Distribución de Inscritos Primera Opción por COD_FICHA',
                            font: { size: 14, weight: 'bold' }
                        },
                        tooltip: {
                            callbacks: {
                                title: (context) => {
                                    const i = context[0]?.dataIndex;
                                    return groupRows[i]?.ficha ?? 'Sin ficha';
                                },
                                label: (context) => {
                                    const i = context.dataIndex;
                                    const programa = groupRows[i]?.programa ?? 'Sin programa';
                                    const primera = groupRows[i]?.primera ?? 0;
                                    const cupo = groupRows[i]?.cupo ?? 0;
                                    const segunda = groupRows[i]?.segunda ?? 0;
                                    const demanda = cupo > 0 ? ((primera / cupo) * 100).toFixed(2) : '0.00';
                                    
                                    const programaLines = splitLongText(programa);
                                    const dataLine = `1ra=${primera}, 2da=${segunda}, cupo=${cupo}, demanda=${demanda}%`;
                                    return [...programaLines, dataLine];
                                }
                            }
                        }
                    }
                }
            };

            const chart = new Chart(ctx, chartConfig);
            state.charts.push(chart);
        }
        
        return; // No continuar con bar/line logic
    } else {
        // Canvas único para bar y line
        if (!programChartContainer) return;
        programChartContainer.innerHTML = '<canvas id="programChart"></canvas>';
        
        const canvas = document.getElementById('programChart');
        if (!canvas) return;
        canvas.style.width = '100% !important';
        canvas.style.height = '100% !important';
        canvas.style.maxWidth = '100%';
        canvas.style.display = 'block';
        config = {
            type: selectedType,
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'CUPO',
                        data: cupos,
                        backgroundColor: 'rgba(0, 48, 77, 0.80)',
                        borderColor: '#00304D',
                        borderWidth: 2,
                        tension: selectedType === 'line' ? 0.3 : undefined,
                    },
                    {
                        label: 'INSCRITOS PRIMERA OPCIÓN',
                        data: inscritosPrimera,
                        backgroundColor: 'rgba(57, 169, 0, 0.80)',
                        borderColor: '#39A900',
                        borderWidth: 2,
                        tension: selectedType === 'line' ? 0.3 : undefined,
                    },
                    {
                        label: 'INSCRITOS SEGUNDA OPCIÓN',
                        data: inscritosSegunda,
                        backgroundColor: 'rgba(245, 158, 11, 0.80)',
                        borderColor: '#f59e0b',
                        borderWidth: 2,
                        tension: selectedType === 'line' ? 0.3 : undefined,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Comparativo por COD_FICHA: CUPO vs PRIMERA y SEGUNDA OPCIÓN',
                        font: { size: 16, weight: 'bold' }
                    },
                    tooltip: {
                        callbacks: {
                            title: (context) => {
                                const i = context[0]?.dataIndex;
                                return rows[i]?.ficha ?? 'Sin ficha';
                            },
                            label: (context) => {
                                const i = context.dataIndex;
                                const programa = rows[i]?.programa ?? 'Sin programa';
                                return splitLongText(programa);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 20,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de aprendices'
                        },
                    }
                }
            }
        };
        
        const ctx = canvas.getContext('2d');
        if (!ctx) return;
        const chart = new Chart(ctx, config);
        state.charts = [chart];
    }
}

/**
 * Renderizar gráfica de porcentaje de demanda por COD_FICHA
 */
function renderDemandChart(rows) {
    const ctx = document.getElementById('demandChart')?.getContext('2d');
    if (!ctx) return;

    const labels = rows.map(r => r.ficha || 'Sin ficha');
    const demanda = rows.map(r => Number(r.demanda_porcentaje || 0));
    const limiteDemanda = rows.map(() => 100);

    if (state.demandChart) {
        state.demandChart.destroy();
    }

    state.demandChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: '% Demanda (Primera Opción / Cupo)',
                    data: demanda,
                    backgroundColor: demanda.map(value => value > 100 ? 'rgba(220, 53, 69, 0.80)' : 'rgba(57, 169, 0, 0.80)'),
                    borderColor: demanda.map(value => value > 100 ? '#dc3545' : '#39A900'),
                    borderWidth: 2,
                },
                {
                    type: 'line',
                    label: 'Límite de Cupo (100%)',
                    data: limiteDemanda,
                    borderColor: '#00304D',
                    backgroundColor: 'rgba(0, 48, 77, 0.20)',
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    tension: 0,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Porcentaje de Demanda por COD_FICHA',
                    font: { size: 16, weight: 'bold' }
                },
                tooltip: {
                    callbacks: {
                        title: (context) => {
                            const i = context[0]?.dataIndex;
                            return rows[i]?.ficha ?? 'Sin ficha';
                        },
                        label: (context) => {
                            const i = context.dataIndex;
                            const programa = rows[i]?.programa ?? 'Sin programa';
                            return splitLongText(programa);
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45,
                        autoSkip: true,
                        maxTicksLimit: 20,
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Demanda (%)'
                    }
                }
            }
        }
    });
}

/**
 * Generar colores para gráfica de pastel
 */
function generateColors(count) {
    const baseColors = [
        'rgba(57, 169, 0, 0.8)',   // Verde SENA
        'rgba(0, 123, 255, 0.8)',  // Azul
        'rgba(255, 193, 7, 0.8)',  // Amarillo
        'rgba(220, 53, 69, 0.8)',  // Rojo
        'rgba(108, 117, 125, 0.8)', // Gris
        'rgba(0, 200, 83, 0.8)',   // Verde esmeralda
        'rgba(156, 39, 176, 0.8)', // Púrpura
        'rgba(255, 152, 0, 0.8)',  // Naranja
    ];

    const colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(baseColors[i % baseColors.length]);
    }
    return colors;
}

/**
 * Renderizar tabla principal con información enriquecida
 */
function renderMainTable(rows) {
    if (!statsTableBody) return;

    statsTableBody.innerHTML = rows.map(r => {
        const demanda = Number(r.demanda_porcentaje || 0);
        const sobrecupo = Number(r.sobrecupo_primera || 0);
        const demandaClass = demanda > 100 ? 'danger' : demanda >= 80 ? 'warning' : 'success';
        
        return `
        <tr>
            <td style="font-weight: 600;">${escapeHtml(String(r.ficha || ''))}</td>
            <td style="font-weight: 500;" title="${escapeHtml(r.programa)}">
                ${truncateText(escapeHtml(r.programa), 50)}
                ${r.nivel ? `<br><small style="color: #666;">${escapeHtml(r.nivel)}</small>` : ''}
            </td>
            <td>${r.cupos || 0}</td>
            <td><strong>${r.inscritos_primera || 0}</strong></td>
            <td>${r.inscritos_segunda || 0}</td>
            <td>
                <span class="badge badge-${demandaClass}">
                    ${demanda}%
                </span>
            </td>
            <td><strong>${sobrecupo}</strong></td>
        </tr>
    `;
    }).join('');
}

/**
 * Truncar texto largo
 */
function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

/**
 * Renderizar tabla comparativa por estado
 */
function renderComparison(rows) {
    if (!comparisonTable) return;

    // Obtener todos los estados únicos
    const estadosSet = new Set();
    rows.forEach(r => Object.keys(r.estados || {}).forEach(e => estadosSet.add(e)));
    const estados = [...estadosSet];

    // Construir encabezado
    const thead = comparisonTable.querySelector('thead');
    thead.innerHTML = `
        <tr>
            <th>COD_FICHA</th>
            <th>Programa</th>
            ${estados.map(e => `<th>${escapeHtml(e)}</th>`).join('')}
            <th>Total</th>
        </tr>
    `;

    // Construir filas
    const tbody = comparisonTable.querySelector('tbody');
    tbody.innerHTML = rows.map(r => `
        <tr>
            <td style="font-weight: 600;">${escapeHtml(String(r.ficha || ''))}</td>
            <td style="font-weight: 500;">${escapeHtml(r.programa)}</td>
            ${estados.map(e => `<td>${r.estados?.[e] ?? 0}</td>`).join('')}
            <td style="font-weight: 600;">${r.total}</td>
        </tr>
    `).join('');
}

/**
 * Escapar HTML para prevenir XSS
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
