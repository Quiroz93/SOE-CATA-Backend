@extends('admin.layouts.app')

@section('title', 'Gestión de Ofertas')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Gestión de Ofertas Educativas</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.ofertas.create') }}" class="btn btn--primary">
                + Nueva Oferta
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert--success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert--danger">
            {{ session('error') }}
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

        <form method="GET" action="{{ route('admin.ofertas.index') }}" class="filter-form" id="filterForm">
            <div class="filter-form__grid">
                <div class="filter-form__group">
                    <label for="nombre" class="filter-form__label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="filter-form__input" 
                           value="{{ request('nombre') }}" placeholder="Buscar por nombre...">
                </div>

                <div class="filter-form__group">
                    <label for="centro_id" class="filter-form__label">Centro</label>
                    <select name="centro_id" id="centro_id" class="filter-form__select">
                        <option value="">-- Todos los Centros --</option>
                        @foreach($centros as $centro)
                            <option value="{{ $centro->id }}" {{ request('centro_id') == $centro->id ? 'selected' : '' }}>
                                {{ $centro->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-form__group">
                    <label for="estado" class="filter-form__label">Estado</label>
                    <select name="estado" id="estado" class="filter-form__select">
                        <option value="">-- Todos los Estados --</option>
                        <option value="activa" {{ request('estado') === 'activa' ? 'selected' : '' }}>Activa</option>
                        <option value="inactiva" {{ request('estado') === 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                        <option value="vencida" {{ request('estado') === 'vencida' ? 'selected' : '' }}>Vencida</option>
                    </select>
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
                <button type="submit" class="btn btn--primary">🔍 Buscar</button>
                <a href="{{ route('admin.ofertas.index') }}" class="btn btn--secondary">🔄 Limpiar</a>
            </div>
        </form>
    </div>

    <div class="admin-table-card">
        <table class="admin-table">
            <thead class="admin-table__head">
                <tr class="admin-table__head-row">
                    <th class="admin-table__th">Nombre</th>
                    <th class="admin-table__th">Centro</th>
                    <th class="admin-table__th">Programas</th>
                    <th class="admin-table__th">Estado</th>
                    <th class="admin-table__th">Fecha Inicio</th>
                    <th class="admin-table__th">Fecha Fin</th>
                    <th class="admin-table__th admin-table__th--right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ofertas as $oferta)
                <tr class="admin-table__body-row">
                    <td class="admin-table__td">
                        <a href="{{ route('admin.ofertas.show', $oferta) }}" class="link">
                            {{ $oferta->nombre }}
                        </a>
                    </td>
                    <td class="admin-table__td">
                        {{ $oferta->centro->nombre ?? 'N/A' }}
                    </td>
                    <td class="admin-table__td">
                        <span class="badge badge--info">
                            {{ $oferta->ofertaProgramas->count() }} programas
                        </span>
                    </td>
                    <td class="admin-table__td">
                        <span class="badge badge--{{ $oferta->estado === 'activa' ? 'success' : ($oferta->estado === 'vencida' ? 'danger' : 'secondary') }}">
                            {{ ucfirst($oferta->estado) }}
                        </span>
                    </td>
                    <td class="admin-table__td">
                        {{ $oferta->fecha_inicio->format('d/m/Y') }}
                    </td>
                    <td class="admin-table__td">
                        {{ $oferta->fecha_fin->format('d/m/Y') }}
                    </td>
                    <td class="admin-table__td admin-table__td--right">
                        <a href="{{ route('admin.ofertas.show', $oferta) }}" class="btn btn--sm btn--secondary" title="Ver detalles">
                            👁️
                        </a>
                        <a href="{{ route('admin.ofertas.edit', $oferta) }}" class="btn btn--sm btn--secondary" title="Editar">
                            ✏️
                        </a>
                        <form action="{{ route('admin.ofertas.destroy', $oferta) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn--sm btn--danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta oferta?\n\nEsta acción eliminará también todos los programas asociados.')">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="admin-table__body-row">
                    <td colspan="7" class="admin-table__td admin-table__td--center">
                        <p style="margin: 1.5rem 0; color: #666;">No hay ofertas registradas</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($ofertas->hasPages())
    <div class="pagination-wrapper">
        {{ $ofertas->links('admin.pagination.custom') }}
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
        const nombre = document.getElementById('nombre').value.trim();
        const centroId = document.getElementById('centro_id').value.trim();
        const estado = document.getElementById('estado').value.trim();
        const fechaDesde = document.getElementById('fecha_desde').value.trim();
        const fechaHasta = document.getElementById('fecha_hasta').value.trim();
        
        const hasActiveFilters = nombre || centroId || estado || fechaDesde || fechaHasta;
        
        const filterForm = document.getElementById('filterForm');
        const filterToggle = document.querySelector('.filter-toggle__text');
        
        if (!hasActiveFilters) {
            filterForm.classList.add('hidden');
            filterToggle.textContent = '+';
        }
    });
</script>
@endsection
