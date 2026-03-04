@extends('admin.layouts.app')

@section('title', 'Ver Preinscrito')

@section('content')
<div class="admin-page-container">
    <div class="admin-header">
        <h1 class="admin-header__title">Detalles del Preinscrito</h1>
        <a href="{{ route('admin.preinscritos.index') }}" class="btn btn--secondary">
            ← Volver
        </a>
    </div>

    <div class="admin-detail-card">
        <div class="admin-detail-grid">
            <div class="admin-detail-item">
                <span class="admin-detail__label">Nombres</span>
                <span class="admin-detail__value">{{ $preinscrito->nombre }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Apellidos</span>
                <span class="admin-detail__value">{{ $preinscrito->apellido }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Documento</span>
                <span class="admin-detail__value">{{ $preinscrito->documento }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Correo</span>
                <span class="admin-detail__value">{{ $preinscrito->correo }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Estado</span>
                <span class="admin-detail__value">
                    @php($estadoCss = $preinscrito->estado_css_class)
                    <span class="badge {{ in_array($estadoCss, ['preinscrito', 'inscrito', 'convocado_matricula', 'matriculado'], true) ? 'badge--success' : '' }} {{ $estadoCss === 'pendiente' ? 'badge--warning' : '' }} {{ in_array($estadoCss, ['rechazado', 'no_admitido', 'cancelado'], true) ? 'badge--danger' : '' }}">
                        {{ $preinscrito->estado_label }}
                    </span>
                </span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Programa</span>
                <span class="admin-detail__value">{{ $preinscrito->ofertaPrograma->programa->nombre ?? 'N/A' }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Oferta</span>
                <span class="admin-detail__value">{{ $preinscrito->ofertaPrograma->oferta->nombre ?? 'N/A' }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Fecha de Creación</span>
                <span class="admin-detail__value">{{ $preinscrito->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Última Actualización</span>
                <span class="admin-detail__value">{{ $preinscrito->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <div class="admin-detail-actions">
            <a href="{{ route('admin.preinscritos.edit', $preinscrito) }}" class="btn btn--primary">
                Editar
            </a>
            <form action="{{ route('admin.preinscritos.destroy', $preinscrito) }}" method="POST" class="admin-detail__form">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('¿Eliminar este preinscrito?')" class="btn btn--danger">
                    Eliminar
                </button>
            </form>
        </div>
    </div>

    <!-- Sección de Novedades -->
    <div class="admin-detail-card" style="margin-top: 2rem;">
        <div class="admin-header" style="margin-bottom: 1.5rem;">
            <h2 class="admin-header__title" style="font-size: 1.5rem; margin: 0;">📋 Novedades del Preinscrito</h2>
            <div class="admin-header__actions">
                <a href="{{ route('admin.novedades.create', ['preinscrito_id' => $preinscrito->id]) }}" class="btn btn--primary">
                    + Nueva Novedad
                </a>
            </div>
        </div>

        @if($preinscrito->novedades->count() > 0)
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead class="admin-table__head">
                        <tr class="admin-table__head-row">
                            <th class="admin-table__th">Tipo de Novedad</th>
                            <th class="admin-table__th">Detalle</th>
                            <th class="admin-table__th">Fecha de Registro</th>
                            <th class="admin-table__th admin-table__th--right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($preinscrito->novedades as $novedad)
                        <tr class="admin-table__body-row">
                            <td class="admin-table__td">
                                <span class="badge badge--info">{{ $novedad->tipoNovedad->nombre }}</span>
                            </td>
                            <td class="admin-table__td">
                                <div style="max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $novedad->detalle ?? 'Sin detalle' }}
                                </div>
                            </td>
                            <td class="admin-table__td">
                                {{ $novedad->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="admin-table__td admin-table__td--right">
                                <div class="admin-table__actions">
                                    <a href="{{ route('admin.novedades.show', $novedad) }}" 
                                       class="btn btn--sm btn--secondary" 
                                       title="Ver detalle">
                                        👁️ Ver
                                    </a>
                                    <a href="{{ route('admin.novedades.edit', $novedad) }}" 
                                       class="btn btn--sm btn--secondary" 
                                       title="Editar">
                                        ✏️ Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert--info" style="text-align: center;">
                <p style="margin: 0;">
                    📭 No hay novedades registradas para este preinscrito.
                </p>
                <a href="{{ route('admin.novedades.create', ['preinscrito_id' => $preinscrito->id]) }}" 
                   class="btn btn--primary" 
                   style="margin-top: 1rem;">
                    + Registrar Primera Novedad
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
