@extends('admin.layouts.app')

@section('title', 'Gestión de Novedades')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Gestión de Novedades</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.tipo-novedad.index') }}" class="btn btn--secondary">
                ⚙️ Gestionar Tipos
            </a>
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

    <!-- Sección de Filtros -->
    <div class="filter-card">
        <div class="filter-card__header">
            <h3 class="filter-card__title">🔍 Filtros de Búsqueda</h3>
            <button type="button" class="filter-toggle" onclick="toggleFilterForm()">
                <span class="filter-toggle__text">-</span>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.novedades.index') }}" class="filter-form" id="filterForm">
            <div class="filter-form__grid">
                <div class="filter-form__group">
                    <label for="tipo_novedad_id" class="filter-form__label">Tipo de Novedad</label>
                    <select name="tipo_novedad_id" id="tipo_novedad_id" class="filter-form__select">
                        <option value="">-- Todos los Tipos --</option>
                        @foreach($tiposNovedad as $tipo)
                            <option value="{{ $tipo->id }}" 
                                {{ request('tipo_novedad_id') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-form__group">
                    <label for="nombre" class="filter-form__label">Nombre del Preinscrito</label>
                    <input type="text" name="nombre" id="nombre" class="filter-form__input" 
                           placeholder="Buscar por nombre..." value="{{ request('nombre') }}">
                </div>

                <div class="filter-form__group">
                    <label for="documento" class="filter-form__label">Documento</label>
                    <input type="text" name="documento" id="documento" class="filter-form__input" 
                           placeholder="Buscar por documento..." value="{{ request('documento') }}">
                </div>

                <div class="filter-form__group">
                    <label for="fecha_desde" class="filter-form__label">Fecha Desde</label>
                    <input type="date" name="fecha_desde" id="fecha_desde" class="filter-form__input" 
                           value="{{ request('fecha_desde') }}">
                </div>

                <div class="filter-form__group">
                    <label for="fecha_hasta" class="filter-form__label">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" id="fecha_hasta" class="filter-form__input" 
                           value="{{ request('fecha_hasta') }}">
                </div>
            </div>

            <div class="filter-form__actions">
                <button type="submit" class="btn btn--primary">
                    🔍 Buscar
                </button>
                <a href="{{ route('admin.novedades.index') }}" class="btn btn--secondary">
                    ↻ Limpiar Filtros
                </a>
            </div>
        </form>
    </div>

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

<script>
    function toggleFilterForm() {
        const filterForm = document.getElementById('filterForm');
        const filterToggle = document.querySelector('.filter-toggle__text');
        
        filterForm.classList.toggle('hidden');
        filterToggle.textContent = filterForm.classList.contains('hidden') ? '+' : '-';
    }

    // Mostrar el formulario si hay filtros activos
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si hay filtros activos usando los valores de los inputs
        const tipoNovedadId = document.getElementById('tipo_novedad_id').value.trim();
        const nombre = document.getElementById('nombre').value.trim();
        const documento = document.getElementById('documento').value.trim();
        const fechaDesde = document.getElementById('fecha_desde').value.trim();
        const fechaHasta = document.getElementById('fecha_hasta').value.trim();
        
        const hasActiveFilters = tipoNovedadId || nombre || documento || fechaDesde || fechaHasta;
        
        const filterForm = document.getElementById('filterForm');
        const filterToggle = document.querySelector('.filter-toggle__text');
        
        if (!hasActiveFilters) {
            filterForm.classList.add('hidden');
            filterToggle.textContent = '+';
        }
    });
</script>
@endsection
