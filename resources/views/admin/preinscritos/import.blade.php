@extends('admin.layouts.app')

@section('title', 'Importar Preinscritos')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Importar Preinscritos desde Excel</h1>
        <a href="{{ route('admin.preinscritos.index') }}" class="btn btn--secondary">
            ← Volver
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert--danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="import-section">
        <div class="import-card">
            <h2 class="import-card__title">Cargar Archivo Excel</h2>
            
            <form action="{{ route('admin.preinscritos.handleImport') }}" method="POST" enctype="multipart/form-data" class="import-form">
                @csrf

                <div class="form-group">
                    <label for="oferta_id" class="form-label">Oferta destino para la importación:</label>
                    <select id="oferta_id" name="oferta_id" class="form-input" required>
                        <option value="">-- Seleccionar oferta --</option>
                        @foreach($ofertas as $oferta)
                            <option value="{{ $oferta->id }}" {{ old('oferta_id') == $oferta->id ? 'selected' : '' }}>
                                Oferta #{{ $oferta->id }} - {{ $oferta->nombre ?? 'Sin nombre' }}
                            </option>
                        @endforeach
                    </select>
                    @error('oferta_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                    <p class="form-helper">
                        La relación final se resolverá con la oferta seleccionada + Número Ficha de cada fila.
                    </p>
                </div>
                
                <div class="form-group">
                    <label for="excel_file" class="form-label">Selecciona archivo Excel:</label>
                    <input 
                        type="file" 
                        id="excel_file" 
                        name="excel_file" 
                        accept=".xlsx,.xls,.csv"
                        class="form-input form-input--file"
                        required
                    >
                    @error('excel_file')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                    <p class="form-helper">
                        Formatos soportados: .xlsx, .xls, .csv (Máximo: 5 MB)
                    </p>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn--primary">
                        📤 Importar Preinscritos
                    </button>
                    <a href="{{ route('admin.preinscritos.downloadTemplate') }}" class="btn btn--secondary">
                        📋 Descargar Plantilla
                    </a>
                </div>
            </form>
        </div>

        <div class="import-instructions">
            <h3 class="import-instructions__title">📌 Instrucciones de Importación</h3>
            
            <div class="instruction-item">
                <h4>1. Descargar la Plantilla Actualizada</h4>
                <p>Haz clic en <strong>"📋 Descargar Plantilla"</strong> para obtener un archivo Excel con:</p>
                <ul>
                    <li>Logo SENA institucional en el encabezado</li>
                    <li>Fecha y hora de actualización de la plantilla</li>
                    <li>Formato estandarizado según normas SENA</li>
                    <li>Listas desplegables con datos reales de la base de datos</li>
                </ul>
            </div>
            
            <div class="instruction-item">
                <h4>2. Completar los Datos Obligatorios</h4>
                <p>La plantilla incluye 3 ejemplos de referencia. Puedes eliminarlos y agregar tus datos:</p>
                <ul>
                    <li><strong>Nombre Completo:</strong> Ingresa nombre y apellido completo (texto libre)</li>
                    <li><strong>Cédula:</strong> Documento de identidad sin puntos ni espacios (solo números)</li>
                    <li><strong>Correo Electrónico:</strong> Email válido y activo (formato: ejemplo@dominio.com)</li>
                </ul>
            </div>
            
            <div class="instruction-item">
                <h4>3. Usar las Listas Desplegables Dinámicas</h4>
                <p><strong>¡IMPORTANTE!</strong> Las columnas <strong>Programa</strong> y <strong>Estado</strong> tienen listas desplegables:</p>
                <ul>
                    <li><strong>Programa:</strong> Click en la celda → flecha desplegable → seleccionar programa vigente de la lista actualizada desde la base de datos</li>
                    <li><strong>Estado:</strong> Click en la celda → flecha desplegable → seleccionar estado válido de la lista</li>
                    <li>⚠️ <strong>No escribir manualmente</strong>, usar solo las listas desplegables para evitar errores</li>
                </ul>
            </div>

            <div class="instruction-item">
                <h4>4. Seleccionar Oferta Destino</h4>
                <ul>
                    <li>Antes de importar, selecciona la <strong>oferta destino</strong> en el formulario</li>
                    <li>El sistema usará el <strong>Número Ficha</strong> de cada fila para encontrar el programa dentro de esa oferta</li>
                    <li>La columna Programa del Excel se mantiene como referencia visual</li>
                </ul>
            </div>
            
            <div class="instruction-item">
                <h4>5. Validar y Guardar</h4>
                <ul>
                    <li>Revisa que todos los campos obligatorios estén completos</li>
                    <li>Verifica que los correos sean válidos (Excel no validará el formato)</li>
                    <li>Guarda el archivo Excel (.xlsx recomendado)</li>
                    <li>No modifiques el encabezado ni el formato de las columnas</li>
                </ul>
            </div>
            
            <div class="instruction-item">
                <h4>6. Cargar y Procesar</h4>
                <p>En esta página:</p>
                <ul>
                    <li>Selecciona la oferta destino de la importación</li>
                    <li>Haz clic en el botón de selección de archivo</li>
                    <li>Selecciona tu archivo Excel completado</li>
                    <li>Haz clic en <strong>"📤 Importar Preinscritos"</strong></li>
                    <li>Espera el procesamiento (puede tardar según la cantidad de registros)</li>
                </ul>
            </div>
            
            <div class="instruction-item">
                <h4>7. Revisar Resultados</h4>
                <p>Al finalizar verás:</p>
                <ul>
                    <li>✅ Número de registros importados exitosamente</li>
                    <li>⚠️ Listado de errores por fila (si los hay)</li>
                    <li>Razones de rechazo: duplicados, correos inválidos, programas no encontrados, etc.</li>
                </ul>
            </div>
        </div>

        <div class="import-notes">
            <h3 class="import-notes__title">⚠️ Notas Importantes</h3>
            <ul>
                <li><strong>Campos obligatorios:</strong> Nombre, Cédula y Correo (sin estos no se importará el registro)</li>
                <li><strong>Oferta obligatoria:</strong> Debes seleccionar la oferta destino antes de importar</li>
                <li><strong>Número Ficha obligatorio:</strong> Se usa para relacionar cada fila con el programa correcto dentro de la oferta seleccionada</li>
                <li><strong>Correos únicos:</strong> Los correos deben ser válidos y no duplicados en el sistema</li>
                <li><strong>Programas actualizados:</strong> La lista de programas en la plantilla refleja solo los programas publicados y vigentes</li>
                <li><strong>Listas desplegables:</strong> Usa SIEMPRE las listas desplegables en las columnas Programa y Estado</li>
                <li><strong>Duplicados:</strong> Los preinscritos con mismo documento Y correo serán ignorados</li>
                <li><strong>Estado por defecto:</strong> Si no se especifica, se asignará "pendiente" automáticamente</li>
                <li><strong>Límite de archivo:</strong> Máximo 5 MB por archivo Excel</li>
                <li><strong>Capacidad:</strong> La plantilla soporta hasta 100 registros con validación automática</li>
                <li><strong>Fecha de plantilla:</strong> Verifica la fecha en el encabezado para asegurar que uses la versión más reciente</li>
            </ul>
        </div>
    </div>
</div>

<style>
    .import-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        margin-top: 24px;
    }

    @media (max-width: 1024px) {
        .import-section {
            grid-template-columns: 1fr;
        }
    }

    .import-card {
        background: white;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .import-card__title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0 0 20px 0;
    }

    .import-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-helper {
        font-size: 12px;
        color: #999;
        margin-top: 6px;
    }

    .form-input--file {
        padding: 12px;
        border: 2px dashed #39A900;
        border-radius: 6px;
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .import-instructions,
    .import-notes {
        background: white;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .import-instructions__title,
    .import-notes__title {
        font-size: 16px;
        font-weight: 600;
        color: #00304D;
        margin: 0 0 16px 0;
    }

    .instruction-item {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .instruction-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .instruction-item h4 {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin: 0 0 8px 0;
    }

    .instruction-item p {
        font-size: 13px;
        color: #666;
        margin: 0;
    }

    .instruction-item ul {
        margin: 8px 0 0 0;
        padding-left: 20px;
        font-size: 13px;
        color: #666;
    }

    .instruction-item li {
        margin: 6px 0;
    }

    .import-notes ul {
        margin: 0;
        padding-left: 20px;
        font-size: 13px;
        color: #666;
    }

    .import-notes li {
        margin: 8px 0;
    }

    .btn--secondary {
        background-color: #6C757D;
        color: white;
        text-decoration: none;
        display: inline-block;
    }

    .btn--secondary:hover {
        background-color: #5A6268;
    }
</style>
@endsection
