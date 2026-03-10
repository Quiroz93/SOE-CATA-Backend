@extends('admin.layouts.app')

@section('title', 'Detalle de Programa')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Detalle del Programa</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.programas.edit', $programa) }}" class="btn btn--secondary">
                ✏️ Editar
            </a>
            <a href="{{ route('admin.programas.index') }}" class="btn btn--secondary">
                ← Volver
            </a>
        </div>
    </div>

    <div class="admin-detail-card">
        <div class="detail-section">
            <h2 class="detail-section__title">Información General</h2>
            
            <div class="detail-row">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">{{ $programa->nombre }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Slug:</div>
                <div class="detail-value">{{ $programa->slug ?? 'N/A' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Ficha:</div>
                <div class="detail-value">{{ $programa->ficha ?? 'N/A' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Estado:</div>
                <div class="detail-value">
                    @if($programa->estado)
                        <span class="badge badge--{{ $programa->estado->value === 'publicado' ? 'success' : ($programa->estado->value === 'borrador' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($programa->estado->value) }}
                        </span>
                    @else
                        <span class="badge badge--secondary">Sin estado</span>
                    @endif
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Descripción:</div>
                <div class="detail-value" style="white-space: pre-wrap;">
                    {{ $programa->descripcion ?? 'Sin descripción' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Atributos por oferta:</div>
                <div class="detail-value">La modalidad, jornada y municipio se definen al asociar este programa a una oferta.</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Fecha de Creación:</div>
                <div class="detail-value">
                    {{ $programa->created_at->format('d/m/Y H:i:s') }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Última Actualización:</div>
                <div class="detail-value">
                    {{ $programa->updated_at->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.programas.edit', $programa) }}" class="btn btn--primary">
                ✏️ Editar
            </a>
            <form action="{{ route('admin.programas.destroy', $programa) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn--danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este programa?')">
                    🗑️ Eliminar
                </button>
            </form>
            <a href="{{ route('admin.programas.index') }}" class="btn btn--secondary">
                ← Volver al Listado
            </a>
        </div>
    </div>
</div>
@endsection
