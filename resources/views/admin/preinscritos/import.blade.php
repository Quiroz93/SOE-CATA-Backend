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
                <h4>1. Descargar la Plantilla</h4>
                <p>Haz clic en "Descargar Plantilla" para obtener un archivo Excel con el formato correcto.</p>
            </div>
            
            <div class="instruction-item">
                <h4>2. Completar los Datos</h4>
                <p>Rellena la plantilla con los datos de los preinscritos. Asegúrate de seguir el formato especificado.</p>
            </div>
            
            <div class="instruction-item">
                <h4>3. Validar los Campos</h4>
                <ul>
                    <li><strong>Nombre Completo:</strong> Ingresa el nombre y apellido completo</li>
                    <li><strong>Cédula:</strong> Documento de identidad del preinscrito</li>
                    <li><strong>Correo Electrónico:</strong> Email válido y único</li>
                    <li><strong>Programa:</strong> Selecciona un programa disponible</li>
                    <li><strong>Estado:</strong> pendiente, aceptado o rechazado</li>
                </ul>
            </div>
            
            <div class="instruction-item">
                <h4>4. Cargar el Archivo</h4>
                <p>Selecciona el archivo completado y haz clic en "Importar Preinscritos".</p>
            </div>
            
            <div class="instruction-item">
                <h4>5. Revisar Resultados</h4>
                <p>Se mostrará un resumen de los registros importados y cualquier error encontrado.</p>
            </div>
        </div>

        <div class="import-notes">
            <h3 class="import-notes__title">⚠️ Notas Importantes</h3>
            <ul>
                <li>Los campos <strong>Nombre, Cédula y Correo</strong> son obligatorios</li>
                <li>Los correos deben ser válidos y únicos</li>
                <li>Los preinscritos duplicados (mismo documento y correo) serán ignorados</li>
                <li>El estado por defecto es "pendiente" si no se especifica</li>
                <li>Máximo 5 MB por archivo</li>
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
