@extends('admin.layouts.app')

@section('title', 'Gestión de Novedades')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Gestión de Novedades</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.novedades.create') }}" class="btn btn--primary">
                + Nueva Novedad
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert--success">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead class="admin-table__head">
                <tr class="admin-table__head-row">
                    <th class="admin-table__th">Preinscrito</th>
                    <th class="admin-table__th">Tipo de Novedad</th>
                    <th class="admin-table__th">Detalle</th>
                    <th class="admin-table__th">Fecha de Registro</th>
                    <th class="admin-table__th admin-table__th--right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($novedades as $novedad)
                <tr class="admin-table__body-row">
                    <td class="admin-table__td">
                        <a href="{{ route('admin.preinscritos.show', $novedad->preinscrito) }}" class="link">
                            {{ $novedad->preinscrito->nombre }}
                        </a>
                    </td>
                    <td class="admin-table__td">
                        <span class="badge badge--info">{{ $novedad->tipoNovedad->nombre }}</span>
                    </td>
                    <td class="admin-table__td" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                        {{ Str::limit($novedad->detalle ?? 'Sin detalle', 50) }}
                    </td>
                    <td class="admin-table__td">
                        {{ $novedad->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="admin-table__td admin-table__td--right">
                        <a href="{{ route('admin.novedades.show', $novedad) }}" class="btn btn--sm btn--secondary" title="Ver detalles">
                            👁️
                        </a>
                        <a href="{{ route('admin.novedades.edit', $novedad) }}" class="btn btn--sm btn--secondary" title="Editar">
                            ✏️
                        </a>
                        <form action="{{ route('admin.novedades.destroy', $novedad) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn--sm btn--danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta novedad?')">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="admin-table__body-row">
                    <td colspan="5" class="admin-table__td admin-table__td--center">
                        <p style="margin: 1.5rem 0; color: #666;">No hay novedades registradas</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($novedades->hasPages())
    <div class="pagination-wrapper">
        {{ $novedades->links('admin.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
