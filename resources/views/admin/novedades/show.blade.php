@extends('admin.layouts.app')

@section('title', 'Detalles de Novedad')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Detalles de Novedad</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.novedades.edit', $novedad) }}" class="btn btn--secondary">
                ✏️ Editar
            </a>
            <a href="{{ route('admin.novedades.index') }}" class="btn btn--secondary">
                ← Volver
            </a>
        </div>
    </div>

    <div class="admin-detail-card">
        <div class="detail-section">
            <h2 class="detail-section__title">Información de la Novedad</h2>
            
            <div class="detail-row">
                <div class="detail-label">Preinscrito:</div>
                <div class="detail-value">
                    <a href="{{ route('admin.preinscritos.show', $novedad->preinscrito) }}" class="link">
                        {{ $novedad->preinscrito->nombre }}
                    </a>
                    <span class="detail-info">({{ $novedad->preinscrito->documento }})</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Correo del Preinscrito:</div>
                <div class="detail-value">
                    <a href="mailto:{{ $novedad->preinscrito->correo }}" class="link">
                        {{ $novedad->preinscrito->correo }}
                    </a>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tipo de Novedad:</div>
                <div class="detail-value">
                    <span class="badge badge--info">{{ $novedad->tipoNovedad->nombre }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Descripción del Tipo:</div>
                <div class="detail-value">
                    {{ $novedad->tipoNovedad->descripcion ?? 'Sin descripción' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Detalle:</div>
                <div class="detail-value" style="white-space: pre-wrap;">
                    {{ $novedad->detalle ?? 'Sin detalle adicional' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Fecha de Registro:</div>
                <div class="detail-value">
                    {{ $novedad->created_at->format('d/m/Y H:i:s') }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Última Actualización:</div>
                <div class="detail-value">
                    {{ $novedad->updated_at->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.novedades.edit', $novedad) }}" class="btn btn--primary">
                ✏️ Editar
            </a>
            <form action="{{ route('admin.novedades.destroy', $novedad) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn--danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta novedad?')">
                    🗑️ Eliminar
                </button>
            </form>
            <a href="{{ route('admin.novedades.index') }}" class="btn btn--secondary">
                ← Volver al Listado
            </a>
        </div>
    </div>
</div>

<style>
    .detail-section {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .detail-section__title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #39A900;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 2rem;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #555;
        font-size: 0.95rem;
    }

    .detail-value {
        color: #333;
        word-break: break-word;
    }

    .detail-info {
        color: #999;
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }

    .link {
        color: #39A900;
        text-decoration: none;
    }

    .link:hover {
        text-decoration: underline;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .badge--info {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    @media (max-width: 768px) {
        .detail-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .detail-label {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
    }
</style>
@endsection
