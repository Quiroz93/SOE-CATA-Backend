@extends('admin.layouts.app')

@section('title', 'Detalle de Oferta')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Detalle de la Oferta</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.ofertas.edit', $oferta) }}" class="btn btn--secondary">
                ✏️ Editar
            </a>
            <a href="{{ route('admin.ofertas.index') }}" class="btn btn--secondary">
                ← Volver
            </a>
        </div>
    </div>

    <div class="admin-detail-card">
        <div class="detail-section">
            <h2 class="detail-section__title">Información General</h2>
            
            <div class="detail-row">
                <div class="detail-label">Nombre:</div>
                <div class="detail-value">{{ $oferta->nombre }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Centro:</div>
                <div class="detail-value">{{ $oferta->centro->nombre ?? 'N/A' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Estado:</div>
                <div class="detail-value">
                    <span class="badge badge--{{ $oferta->estado === 'activa' ? 'success' : ($oferta->estado === 'vencida' ? 'danger' : 'secondary') }}">
                        {{ ucfirst($oferta->estado) }}
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Fecha de Inicio:</div>
                <div class="detail-value">{{ $oferta->fecha_inicio->format('d/m/Y') }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Fecha de Fin:</div>
                <div class="detail-value">{{ $oferta->fecha_fin->format('d/m/Y') }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Descripción:</div>
                <div class="detail-value" style="white-space: pre-wrap;">
                    {{ $oferta->descripcion ?? 'Sin descripción' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Fecha de Creación:</div>
                <div class="detail-value">
                    {{ $oferta->created_at->format('d/m/Y H:i:s') }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Última Actualización:</div>
                <div class="detail-value">
                    {{ $oferta->updated_at->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>

        <!-- Programas Asociados -->
        @if($oferta->ofertaProgramas->count() > 0)
        <div class="detail-section">
            <h2 class="detail-section__title">Programas Asociados ({{ $oferta->ofertaProgramas->count() }})</h2>
            
            <div class="admin-table-card" style="margin-top: 1rem;">
                <table class="admin-table">
                    <thead class="admin-table__head">
                        <tr class="admin-table__head-row">
                            <th class="admin-table__th">Programa</th>
                            <th class="admin-table__th">Centro</th>
                            <th class="admin-table__th">Instructor</th>
                            <th class="admin-table__th">Cupos</th>
                            <th class="admin-table__th">Modalidad</th>
                            <th class="admin-table__th">Jornada</th>
                            <th class="admin-table__th">Municipio</th>
                            <th class="admin-table__th">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($oferta->ofertaProgramas as $ofertaPrograma)
                        <tr class="admin-table__body-row">
                            <td class="admin-table__td">
                                {{ $ofertaPrograma->programa->nombre ?? 'N/A' }}
                            </td>
                            <td class="admin-table__td">
                                {{ $ofertaPrograma->centro->nombre ?? 'N/A' }}
                            </td>
                            <td class="admin-table__td">
                                {{ $ofertaPrograma->instructor->nombre ?? 'N/A' }}
                            </td>
                            <td class="admin-table__td">
                                <span class="badge badge--info">{{ $ofertaPrograma->cupos }}</span>
                            </td>
                            <td class="admin-table__td">
                                {{ $ofertaPrograma->modalidad ?? 'N/A' }}
                            </td>
                            <td class="admin-table__td">
                                {{ $ofertaPrograma->jornada ?? 'N/A' }}
                            </td>
                            <td class="admin-table__td">
                                {{ $ofertaPrograma->municipio ?? 'N/A' }}
                            </td>
                            <td class="admin-table__td">
                                <span class="badge badge--{{ $ofertaPrograma->estado ? 'success' : 'secondary' }}">
                                    {{ $ofertaPrograma->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('admin.ofertas.edit', $oferta) }}" class="btn btn--primary">
                ✏️ Editar
            </a>
            <form action="{{ route('admin.ofertas.destroy', $oferta) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn--danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta oferta?\n\nEsta acción eliminará también todos los programas asociados.')">
                    🗑️ Eliminar
                </button>
            </form>
            <a href="{{ route('admin.ofertas.index') }}" class="btn btn--secondary">
                ← Volver al Listado
            </a>
        </div>
    </div>
</div>
@endsection
