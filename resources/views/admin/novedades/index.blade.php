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

<style>
    .filter-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        margin-bottom: 2rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .filter-card__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #f5f5f5 0%, #fafafa 100%);
        border-bottom: 1px solid #e0e0e0;
        cursor: pointer;
    }

    .filter-card__title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .filter-toggle {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #39A900;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    .filter-toggle__text {
        display: inline-block;
        transition: transform 0.3s ease;
    }

    .filter-form {
        padding: 1.5rem;
    }

    .filter-form__grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-form__group {
        display: flex;
        flex-direction: column;
    }

    .filter-form__label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 0.5rem;
    }

    .filter-form__input,
    .filter-form__select {
        padding: 0.625rem 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.875rem;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .filter-form__input:focus,
    .filter-form__select:focus {
        outline: none;
        border-color: #39A900;
        box-shadow: 0 0 0 3px rgba(57, 169, 0, 0.1);
    }

    .filter-form__input::placeholder {
        color: #999;
    }

    .filter-form__actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .filter-form__actions .btn {
        white-space: nowrap;
    }

    /* Estilos para cuando los filtros están ocultos */
    .filter-form.hidden {
        display: none;
    }

    /* Estilos responsivos */
    @media (max-width: 768px) {
        .filter-form__grid {
            grid-template-columns: 1fr;
        }

        .filter-card__header {
            padding: 1rem;
        }

        .filter-form {
            padding: 1rem;
        }

        .filter-form__actions {
            flex-direction: column;
        }

        .filter-form__actions .btn {
            width: 100%;
        }
    }
</style>

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
