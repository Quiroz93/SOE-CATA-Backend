// Dynamic Chart Wizard - Sistema de Gráficas Dinámicas con Análisis de Excel
// Permite cargar, analizar y generar gráficas personalizadas desde archivos Excel

document.addEventListener('DOMContentLoaded', function() {
    // Estado global del wizard
    const wizardState = {
        currentStep: 1,
        fileData: null,
        analysis: null,
        selectedSheet: 0,
        selectedColumns: [],
        labelColumn: null,
        valueColumns: [],
        chartConfig: {},
        extractedData: null
    };

    let dynamicChart = null;

    // Elementos del DOM
    const dropZone = document.getElementById('dynamicDropZone');
    const fileInput = document.getElementById('dynamicExcelFile');
    const uploadStatus = document.getElementById('dynamicUploadStatus');

    // Paso 1: Configurar zona de carga
    if (dropZone && fileInput) {
        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#39a900';
            dropZone.style.background = '#f0f8f0';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '';
            dropZone.style.background = '';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '';
            dropZone.style.background = '';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileUpload(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileUpload(e.target.files[0]);
            }
        });
    }

    // Función para cargar y analizar archivo
    async function handleFileUpload(file) {
        console.log('[DynamicChart] Iniciando carga de archivo', {
            fileName: file.name,
            fileSize: file.size,
            fileType: file.type
        });

        if (!file.name.match(/\.(xlsx|xls)$/i)) {
            console.warn('[DynamicChart] Archivo inválido - formato no soportado', file.name);
            showUploadMessage('error', 'Por favor, selecciona un archivo Excel válido (.xlsx o .xls)');
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            console.warn('[DynamicChart] Archivo demasiado grande', {
                size: file.size,
                maxSize: 10 * 1024 * 1024
            });
            showUploadMessage('error', 'El archivo es muy grande. El tamaño máximo es 10MB');
            return;
        }

        console.log('[DynamicChart] Enviando archivo al servidor para análisis');
        showUploadMessage('loading', 'Analizando archivo...');

        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('/admin/dashboard/dynamic-chart/analyze', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            console.log('[DynamicChart] Respuesta recibida del servidor', {
                status: response.status,
                ok: response.ok
            });

            const result = await response.json();
            console.log('[DynamicChart] Resultado del análisis', result);

            if (result.success) {
                console.log('[DynamicChart] Análisis exitoso', {
                    fileName: result.analysis.file_name,
                    totalSheets: result.analysis.total_sheets,
                    filePath: result.file_path
                });

                wizardState.fileData = result.file_path;
                wizardState.analysis = result.analysis;
                
                showUploadMessage('success', `✓ Archivo analizado correctamente: ${result.analysis.file_name}`);
                
                setTimeout(() => {
                    goToStep(2);
                    displayAnalysisSummary(result.analysis);
                }, 1000);
            } else {
                console.error('[DynamicChart] Error en análisis', result.error);
                showUploadMessage('error', result.error || 'Error al analizar el archivo');
            }
        } catch (error) {
            console.error('[DynamicChart] Error en handleFileUpload', {
                error: error.message,
                stack: error.stack
            });
            showUploadMessage('error', 'Error de conexión. Por favor, intenta nuevamente.');
        }
    }

    // Mostrar mensaje de carga/error/éxito
    function showUploadMessage(type, message) {
        const icons = {
            loading: '⏳',
            success: '✓',
            error: '✗'
        };

        const colors = {
            loading: '#007bff',
            success: '#28a745',
            error: '#dc3545'
        };

        uploadStatus.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: ${colors[type]}20; border: 2px solid ${colors[type]}; border-radius: 6px; color: ${colors[type]}; font-weight: 600;">
                <span style="font-size: 20px;">${icons[type]}</span>
                <span>${message}</span>
                ${type === 'loading' ? '<div class="loading-spinner"></div>' : ''}
            </div>
        `;
    }

    // Paso 2: Mostrar resumen del análisis
    function displayAnalysisSummary(analysis) {
        console.log('[DynamicChart] Mostrando resumen de análisis', {
            fileName: analysis.file_name,
            totalSheets: analysis.total_sheets,
            sheets: analysis.sheets.map(s => ({ name: s.sheet_name, rows: s.row_count, columns: s.column_count }))
        });

        const container = document.getElementById('fileAnalysisSummary');
        
        let html = `
            <div class="alert alert-info">
                <strong>📄 Archivo:</strong> ${analysis.file_name} | 
                <strong>📊 Hojas:</strong> ${analysis.total_sheets} | 
                <strong>🕒 Análisis:</strong> ${analysis.timestamp}
            </div>
        `;

        // Mostrar resumen de cada hoja
        analysis.sheets.forEach((sheet, index) => {
            html += `
                <div class="analysis-card">
                    <h4>📋 Hoja ${index + 1}: ${sheet.sheet_name}</h4>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
                        <div style="background: #e3f2fd; padding: 15px; border-radius: 6px;">
                            <div style="font-size: 12px; color: #0277bd; font-weight: 600;">Total Filas</div>
                            <div style="font-size: 24px; font-weight: 700; color: #01579b;">${sheet.row_count}</div>
                        </div>
                        <div style="background: #f3e5f5; padding: 15px; border-radius: 6px;">
                            <div style="font-size: 12px; color: #6a1b9a; font-weight: 600;">Total Columnas</div>
                            <div style="font-size: 24px; font-weight: 700; color: #4a148c;">${sheet.column_count}</div>
                        </div>
                        <div style="background: #e8f5e9; padding: 15px; border-radius: 6px;">
                            <div style="font-size: 12px; color: #2e7d32; font-weight: 600;">Filas de Datos</div>
                            <div style="font-size: 24px; font-weight: 700; color: #1b5e20;">${sheet.data_rows_count}</div>
                        </div>
                    </div>

                    ${sheet.metadata.length > 0 ? `
                        <div style="margin-bottom: 15px;">
                            <h5 style="color: #555; font-size: 14px; font-weight: 600; margin-bottom: 10px;">📝 Metadatos Encontrados:</h5>
                            <ul class="metadata-list">
                                ${sheet.metadata.map(meta => {
                                    if (meta.type === 'key_value') {
                                        return `<li><span class="metadata-key">${meta.key}:</span> <span class="metadata-value">${meta.value}</span></li>`;
                                    } else {
                                        return `<li><span class="metadata-value" style="font-style: italic;">${meta.value}</span></li>`;
                                    }
                                }).join('')}
                            </ul>
                        </div>
                    ` : ''}

                    <div>
                        <h5 style="color: #555; font-size: 14px; font-weight: 600; margin-bottom: 10px;">📊 Columnas Identificadas:</h5>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                                <thead>
                                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                        <th style="padding: 10px; text-align: left;">Columna</th>
                                        <th style="padding: 10px; text-align: left;">Tipo</th>
                                        <th style="padding: 10px; text-align: center;">Valores</th>
                                        <th style="padding: 10px; text-align: center;">Únicos</th>
                                        <th style="padding: 10px; text-align: left;">Muestra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${sheet.columns.map(col => `
                                        <tr style="border-bottom: 1px solid #e0e0e0;">
                                            <td style="padding: 10px; font-weight: 600;">${col.name}</td>
                                            <td style="padding: 10px;">
                                                <span style="padding: 4px 8px; background: ${col.data_type === 'numeric' ? '#d4edda' : '#d1ecf1'}; color: ${col.data_type === 'numeric' ? '#155724' : '#0c5460'}; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                                    ${col.data_type === 'numeric' ? '🔢 Numérico' : '📝 Texto'}
                                                </span>
                                            </td>
                                            <td style="padding: 10px; text-align: center;">${col.total_values}</td>
                                            <td style="padding: 10px; text-align: center;">${col.unique_count}</td>
                                            <td style="padding: 10px; font-size: 11px; color: #666;">${col.sample_values.slice(0, 3).join(', ')}...</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    ${sheet.data_sample.length > 0 ? `
                        <div style="margin-top: 15px;">
                            <h5 style="color: #555; font-size: 14px; font-weight: 600; margin-bottom: 10px;">👁️ Vista Previa de Datos (primeras 5 filas):</h5>
                            <div style="overflow-x: auto; max-height: 300px;">
                                <table class="preview-table">
                                    <thead>
                                        <tr>
                                            ${Object.keys(sheet.data_sample[0]).map(key => `<th>${key}</th>`).join('')}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${sheet.data_sample.slice(0, 5).map(row => `
                                            <tr>
                                                ${Object.values(row).map(val => `<td>${val || '-'}</td>`).join('')}
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // Paso 3: Mostrar formulario de selección de datos
    document.getElementById('btnNextStep2')?.addEventListener('click', () => {
        console.log('[DynamicChart] Avanzando al paso 3 - Selección de datos');
        goToStep(3);
        displayDataSelectionForm();
    });

    function displayDataSelectionForm() {
        const sheet = wizardState.analysis.sheets[wizardState.selectedSheet];

        console.log('[DynamicChart] Mostrando formulario de selección', {
            selectedSheet: wizardState.selectedSheet,
            sheetName: sheet.sheet_name,
            availableColumns: sheet.columns.length
        });

        const container = document.getElementById('dataSelectionForm');

        let html = `
            <div class="alert alert-warning">
                <strong>ℹ️ Instrucciones:</strong> Selecciona las columnas que deseas incluir en tu gráfica. 
                Luego indica cuál columna usarás para las etiquetas y cuál(es) para los valores.
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 600; margin-bottom: 10px; color: #333;">
                    Selecciona las Columnas a Incluir:
                </label>
                <div class="columns-grid" id="columnsCheckboxes">
                    ${sheet.columns.map(col => `
                        <div class="column-card">
                            <label style="display: flex; align-items: start; cursor: pointer;">
                                <input type="checkbox" class="column-checkbox" data-index="${col.index}" data-name="${col.name}" style="margin-top: 3px;">
                                <div style="flex: 1;">
                                    <div class="column-name">${col.name}</div>
                                    <div class="column-info">
                                        Tipo: <strong>${col.data_type === 'numeric' ? 'Numérico' : 'Texto'}</strong><br>
                                        Valores: ${col.total_values} | Únicos: ${col.unique_count}
                                    </div>
                                    ${col.sample_values.length > 0 ? `
                                        <div class="sample-data">
                                            Muestra: ${col.sample_values.slice(0, 2).join(', ')}
                                        </div>
                                    ` : ''}
                                </div>
                            </label>
                        </div>
                    `).join('')}
                </div>
            </div>

            <div id="columnRolesSection" style="display: none; margin-top: 25px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
                            Columna para Etiquetas (Eje X): <span style="color: #FF4444;">*</span>
                        </label>
                        <select id="labelColumnSelect" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 4px;">
                            <option value="">Selecciona una columna...</option>
                        </select>
                        <small style="color: #666; font-size: 12px;">Esta columna se usará para nombrar los elementos de la gráfica</small>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
                            Columna(s) para Valores (Eje Y): <span style="color: #FF4444;">*</span>
                        </label>
                        <select id="valueColumnsSelect" multiple style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 4px; min-height: 100px;">
                        </select>
                        <small style="color: #666; font-size: 12px;">Mantén Ctrl/Cmd para seleccionar múltiples columnas</small>
                    </div>
                </div>
            </div>

            <div id="selectionError" class="alert alert-danger" style="display: none; margin-top: 15px;"></div>
        `;

        container.innerHTML = html;

        // Agregar listeners a los checkboxes
        const checkboxes = document.querySelectorAll('.column-checkbox');
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateColumnRoles);
        });
    }

    function updateColumnRoles() {
        const checkboxes = document.querySelectorAll('.column-checkbox:checked');
        const selectedColumns = Array.from(checkboxes).map(cb => ({
            index: parseInt(cb.dataset.index),
            name: cb.dataset.name
        }));

        console.log('[DynamicChart] Columnas seleccionadas actualizadas', {
            count: selectedColumns.length,
            columns: selectedColumns.map(c => c.name)
        });

        wizardState.selectedColumns = selectedColumns;

        const rolesSection = document.getElementById('columnRolesSection');
        const labelSelect = document.getElementById('labelColumnSelect');
        const valueSelect = document.getElementById('valueColumnsSelect');

        if (selectedColumns.length >= 2) {
            rolesSection.style.display = 'block';

            // Actualizar opciones de selects
            labelSelect.innerHTML = '<option value="">Selecciona una columna...</option>' +
                selectedColumns.map(col => `<option value="${col.index}">${col.name}</option>`).join('');

            valueSelect.innerHTML = selectedColumns.map(col => `<option value="${col.index}">${col.name}</option>`).join('');
        } else {
            rolesSection.style.display = 'none';
        }
    }

    // Paso 4: Configuración de gráfica
    document.getElementById('btnNextStep3')?.addEventListener('click', () => {
        console.log('[DynamicChart] Intentando avanzar al paso 4 - Configuración de gráfica');

        const labelSelect = document.getElementById('labelColumnSelect');
        const valueSelect = document.getElementById('valueColumnsSelect');
        const errorDiv = document.getElementById('selectionError');

        if (!labelSelect.value) {
            console.warn('[DynamicChart] Validación fallida - No se seleccionó columna de etiquetas');
            errorDiv.textContent = 'Por favor, selecciona una columna para las etiquetas.';
            errorDiv.style.display = 'block';
            return;
        }

        if (valueSelect.selectedOptions.length === 0) {
            console.warn('[DynamicChart] Validación fallida - No se seleccionaron columnas de valores');
            errorDiv.textContent = 'Por favor, selecciona al menos una columna para los valores.';
            errorDiv.style.display = 'block';
            return;
        }

        wizardState.labelColumn = parseInt(labelSelect.value);
        wizardState.valueColumns = Array.from(valueSelect.selectedOptions).map(opt => parseInt(opt.value));

        console.log('[DynamicChart] Selección de columnas confirmada', {
            labelColumn: wizardState.labelColumn,
            valueColumns: wizardState.valueColumns
        });

        errorDiv.style.display = 'none';
        goToStep(4);
        populateChartConfigDefaults();
    });

    function populateChartConfigDefaults() {
        const sheet = wizardState.analysis.sheets[wizardState.selectedSheet];
        const labelCol = sheet.columns.find(c => c.index === wizardState.labelColumn);
        const valueCol = sheet.columns.find(c => c.index === wizardState.valueColumns[0]);

        document.getElementById('dynamicChartTitle').value = `Reporte de ${sheet.sheet_name}`;
        document.getElementById('dynamicTableTitle').value = `Resumen de ${sheet.sheet_name}`;
    }

    // Paso 5: Vista previa
    document.getElementById('btnNextStep4')?.addEventListener('click', () => {
        console.log('[DynamicChart] Intentando avanzar al paso 5 - Vista previa');

        const title = document.getElementById('dynamicChartTitle').value.trim();
        const tableTitle = document.getElementById('dynamicTableTitle').value.trim();

        if (!title || !tableTitle) {
            console.warn('[DynamicChart] Validación fallida - Campos vacíos');
            alert('Por favor, completa todos los campos obligatorios.');
            return;
        }

        wizardState.chartConfig = {
            title: title,
            type: document.getElementById('dynamicChartType').value,
            color: document.getElementById('dynamicChartColor').value,
            tableTitle: tableTitle
        };

        console.log('[DynamicChart] Configuración de gráfica guardada', wizardState.chartConfig);

        goToStep(5);
        displayDataPreview();
    });

    async function displayDataPreview() {
        console.log('[DynamicChart] Solicitando extracción de datos al servidor');

        const container = document.getElementById('dataPreviewContainer');
        container.innerHTML = '<div style="text-align: center; padding: 40px;"><div class="loading-spinner"></div><p>Cargando vista previa...</p></div>';

        try {
            const requestData = {
                file_path: wizardState.fileData,
                sheet_index: wizardState.selectedSheet,
                header_row: wizardState.analysis.sheets[wizardState.selectedSheet].structure.header_row,
                columns: wizardState.selectedColumns.map(c => c.index),
                label_column: wizardState.selectedColumns.findIndex(c => c.index === wizardState.labelColumn),
                value_columns: wizardState.valueColumns.map(vc => 
                    wizardState.selectedColumns.findIndex(c => c.index === vc)
                ),
                chart_config: wizardState.chartConfig
            };

            console.log('[DynamicChart] Datos de extracción enviados', requestData);

            const response = await fetch('/admin/dashboard/dynamic-chart/extract', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(requestData)
            });

            console.log('[DynamicChart] Respuesta de extracción recibida', {
                status: response.status,
                ok: response.ok
            });

            const result = await response.json();
            console.log('[DynamicChart] Resultado de extracción', result);

            if (result.success) {
                console.log('[DynamicChart] Extracción exitosa', {
                    totalRows: result.total_rows,
                    labelsCount: result.chart_data.labels.length,
                    datasetsCount: result.chart_data.datasets.length
                });

                wizardState.extractedData = result;
                displayPreviewTable(result);
            } else {
                console.error('[DynamicChart] Error en extracción', result.error);
                container.innerHTML = `<div class="alert alert-danger">${result.error}</div>`;
            }
        } catch (error) {
            console.error('[DynamicChart] Error en displayDataPreview', {
                error: error.message,
                stack: error.stack
            });
            container.innerHTML = '<div class="alert alert-danger">Error al cargar la vista previa.</div>';
        }
    }

    function displayPreviewTable(data) {
        const container = document.getElementById('dataPreviewContainer');
        
        let html = `
            <div class="alert alert-success">
                <strong>✓ Datos Extraídos Correctamente</strong><br>
                Total de registros: <strong>${data.total_rows}</strong> | 
                Configuración: <strong>${wizardState.chartConfig.type.toUpperCase()}</strong>
            </div>

            <div style="margin-bottom: 20px;">
                <h5 style="font-weight: 600; margin-bottom: 10px;">Vista Previa de Datos (primeras 10 filas):</h5>
                <div style="overflow-x: auto; max-height: 400px;">
                    <table class="preview-table">
                        <thead>
                            <tr>
                                ${Object.keys(data.data[0] || {}).map(key => `<th>${key}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody>
                            ${data.data.slice(0, 10).map(row => `
                                <tr>
                                    ${Object.values(row).map(val => `<td>${val || '-'}</td>`).join('')}
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="alert alert-info">
                <strong>ℹ️ Resumen de la Gráfica:</strong><br>
                • <strong>Título:</strong> ${wizardState.chartConfig.title}<br>
                • <strong>Tipo:</strong> ${wizardState.chartConfig.type}<br>
                • <strong>Etiquetas:</strong> ${data.chart_data.labels.length} elementos<br>
                • <strong>Series de Datos:</strong> ${data.chart_data.datasets.length}
            </div>
        `;

        container.innerHTML = html;
    }

    // Paso 6: Generar gráfica
    document.getElementById('btnGenerateChart')?.addEventListener('click', () => {
        console.log('[DynamicChart] Generando gráfica final');
        goToStep(6);
        generateFinalChart();
    });

    function generateFinalChart() {
        const data = wizardState.extractedData;
        const config = wizardState.chartConfig;

        console.log('[DynamicChart] Configurando Chart.js', {
            chartType: config.type,
            labelsCount: data.chart_data.labels.length,
            datasetsCount: data.chart_data.datasets.length
        });

        // Preparar configuración de Chart.js
        const chartConfig = {
            type: config.type,
            data: {
                labels: data.chart_data.labels,
                datasets: data.chart_data.datasets.map((dataset, index) => ({
                    label: dataset.label,
                    data: dataset.data,
                    backgroundColor: generateColors(data.chart_data.labels.length, config.color, 0.6),
                    borderColor: generateColors(data.chart_data.labels.length, config.color, 1),
                    borderWidth: 2
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: config.title,
                        font: { size: 18, weight: 'bold' }
                    },
                    legend: {
                        display: data.chart_data.datasets.length > 1,
                        position: 'top'
                    }
                },
                scales: config.type === 'bar' || config.type === 'line' ? {
                    y: {
                        beginAtZero: true
                    }
                } : {}
            }
        };

        // Destruir gráfica anterior si existe
        if (dynamicChart) {
            console.log('[DynamicChart] Destruyendo gráfica anterior');
            dynamicChart.destroy();
        }

        // Crear nueva gráfica
        console.log('[DynamicChart] Creando nueva instancia de Chart.js');
        const ctx = document.getElementById('dynamicChart').getContext('2d');
        dynamicChart = new Chart(ctx, chartConfig);
        console.log('[DynamicChart] Gráfica creada exitosamente');

        // Generar tabla de resultados
        generateResultsTable(data);
    }

    function generateResultsTable(data) {
        const tableTitle = document.getElementById('dynamicResultTableTitle');
        const tableHead = document.getElementById('dynamicResultTableHead');
        const tableBody = document.getElementById('dynamicResultTableBody');

        tableTitle.textContent = wizardState.chartConfig.tableTitle;

        // Generar encabezados
        const headers = Object.keys(data.data[0] || {});
        tableHead.innerHTML = `
            <tr>
                ${headers.map(h => `<th>${h}</th>`).join('')}
            </tr>
        `;

        // Generar filas
        tableBody.innerHTML = data.data.map(row => `
            <tr>
                ${headers.map(h => `<td>${row[h] || '-'}</td>`).join('')}
            </tr>
        `).join('');
    }

    function generateColors(count, baseColor, alpha) {
        const colors = [];
        const base = hexToRgb(baseColor);
        
        for (let i = 0; i < count; i++) {
            const hue = (i * 360 / count) % 360;
            colors.push(`hsla(${hue}, 70%, 50%, ${alpha})`);
        }
        
        return colors;
    }

    function hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    // Botón nueva gráfica
    document.getElementById('btnNewChart')?.addEventListener('click', () => {
        if (confirm('¿Deseas crear una nueva gráfica? Se perderá el progreso actual.')) {
            console.log('[DynamicChart] Reiniciando wizard');
            resetWizard();
        }
    });

    function resetWizard() {
        wizardState.currentStep = 1;
        wizardState.fileData = null;
        wizardState.analysis = null;
        wizardState.selectedColumns = [];
        wizardState.labelColumn = null;
        wizardState.valueColumns = [];
        wizardState.chartConfig = {};
        wizardState.extractedData = null;

        if (dynamicChart) {
            dynamicChart.destroy();
            dynamicChart = null;
        }

        document.getElementById('dynamicExcelFile').value = '';
        uploadStatus.innerHTML = '';

        goToStep(1);
    }

    // Navegación entre pasos
    function goToStep(step) {
        console.log('[DynamicChart] Navegando al paso', step);
        wizardState.currentStep = step;

        // Ocultar todos los pasos
        for (let i = 1; i <= 6; i++) {
            const stepDiv = document.getElementById(`dynamicStep${i}`);
            if (stepDiv) {
                stepDiv.style.display = i === step ? 'block' : 'none';
            }
        }

        // Actualizar indicador de progreso
        const progressSteps = document.querySelectorAll('.progress-step');
        progressSteps.forEach((ps, index) => {
            ps.classList.remove('active', 'completed');
            if (index + 1 === step) {
                ps.classList.add('active');
            } else if (index + 1 < step) {
                ps.classList.add('completed');
            }
        });

        // Scroll al top
        document.getElementById('genericChartFormContainer')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Botones de navegación
    document.getElementById('btnPrevStep2')?.addEventListener('click', () => goToStep(1));
    document.getElementById('btnPrevStep3')?.addEventListener('click', () => goToStep(2));
    document.getElementById('btnPrevStep4')?.addEventListener('click', () => goToStep(3));
    document.getElementById('btnPrevStep5')?.addEventListener('click', () => goToStep(4));

    // Descarga de gráfica (implementar según necesidad)
    document.getElementById('btnDownloadChart')?.addEventListener('click', () => {
        if (dynamicChart) {
            const url = dynamicChart.toBase64Image();
            const link = document.createElement('a');
            link.download = `grafica_${Date.now()}.png`;
            link.href = url;
            link.click();
        }
    });
});
