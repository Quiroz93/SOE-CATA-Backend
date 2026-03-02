@extends('admin.layouts.app')

@section('title', 'Gestión de Programas')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Gestión de Programas</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.programas.create') }}" class="btn btn--primary">
                + Nuevo Programa
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

        <form method="GET" action="{{ route('admin.programas.index') }}" class="filter-form" id="filterForm">
            <div class="filter-form__grid">
                <div class="filter-form__group">
                    <label for="nombre" class="filter-form__label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-input" 
                           value="{{ request('nombre') }}" placeholder="Buscar por nombre...">
                </div>

                <div class="filter-form__group">
                    <label for="ficha" class="filter-form__label">Ficha</label>
                    <input type="text" name="ficha" id="ficha" class="form-input" 
                           value="{{ request('ficha') }}" placeholder="Buscar por ficha...">
                </div>

                <div class="filter-form__group">
                    <label for="estado" class="filter-form__label">Estado</label>
                    <select name="estado" id="estado" class="filter-form__select">
                        <option value="">-- Todos los Estados --</option>
                        <option value="borrador" {{ request('estado') === 'borrador' ? 'selected' : '' }}>Borrador</option>
                        <option value="publicado" {{ request('estado') === 'publicado' ? 'selected' : '' }}>Publicado</option>
                        <option value="archivado" {{ request('estado') === 'archivado' ? 'selected' : '' }}>Archivado</option>
                    </select>
                </div>

                <div class="filter-form__group">
                    <label for="modalidad" class="filter-form__label">Modalidad</label>
                    <select name="modalidad" id="modalidad" class="filter-form__select">
                        <option value="">-- Todas las Modalidades --</option>
                        <option value="Presencial" {{ request('modalidad') === 'Presencial' ? 'selected' : '' }}>Presencial</option>
                        <option value="Virtual" {{ request('modalidad') === 'Virtual' ? 'selected' : '' }}>Virtual</option>
                        <option value="Mixta" {{ request('modalidad') === 'Mixta' ? 'selected' : '' }}>Mixta</option>
                    </select>
                </div>

                <div class="filter-form__group">
                    <label for="municipio" class="filter-form__label">Municipio</label>
                    <input type="text" name="municipio" id="municipio" class="form-input" 
                           value="{{ request('municipio') }}" placeholder="Buscar por municipio...">
                </div>
            </div>

            <div class="filter-form__actions">
                <button type="submit" class="btn btn--primary">🔍 Buscar</button>
                <a href="{{ route('admin.programas.index') }}" class="btn btn--secondary">🔄 Limpiar</a>
            </div>
        </form>
    </div>

    <div class="admin-table-card">
        <table class="admin-table">
            <thead class="admin-table__head">
                <tr class="admin-table__head-row">
                    <th class="admin-table__th">Nombre</th>
                    <th class="admin-table__th">Ficha</th>
                    <th class="admin-table__th">Estado</th>
                    <th class="admin-table__th">Modalidad</th>
                    <th class="admin-table__th">Municipio</th>
                    <th class="admin-table__th admin-table__th--right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programas as $programa)
                <tr class="admin-table__body-row">
                    <td class="admin-table__td">
                        <a href="{{ route('admin.programas.show', $programa) }}" class="link">
                            {{ $programa->nombre }}
                        </a>
                    </td>
                    <td class="admin-table__td">
                        {{ $programa->ficha ?? 'N/A' }}
                    </td>
                    <td class="admin-table__td">
                        @if($programa->estado)
                            <span class="badge badge--{{ $programa->estado->value === 'publicado' ? 'success' : ($programa->estado->value === 'borrador' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($programa->estado->value) }}
                            </span>
                        @else
                            <span class="badge badge--secondary">Sin estado</span>
                        @endif
                    </td>
                    <td class="admin-table__td">
                        {{ $programa->modalidad ?? 'N/A' }}
                    </td>
                    <td class="admin-table__td">
                        {{ $programa->municipio ?? 'N/A' }}
                    </td>
                    <td class="admin-table__td admin-table__td--right">
                        <a href="{{ route('admin.programas.show', $programa) }}" class="btn btn--sm btn--secondary" title="Ver detalles">
                            👁️
                        </a>
                        <a href="{{ route('admin.programas.edit', $programa) }}" class="btn btn--sm btn--secondary" title="Editar">
                            ✏️
                        </a>
                        <form action="{{ route('admin.programas.destroy', $programa) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn--sm btn--danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este programa?')">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="admin-table__body-row">
                    <td colspan="6" class="admin-table__td admin-table__td--center">
                        <p style="margin: 1.5rem 0; color: #666;">No hay programas registrados</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($programas->hasPages())
    <div class="pagination-wrapper">
        {{ $programas->links('admin.pagination.custom') }}
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
        const ficha = document.getElementById('ficha').value.trim();
        const estado = document.getElementById('estado').value.trim();
        const modalidad = document.getElementById('modalidad').value.trim();
        const municipio = document.getElementById('municipio').value.trim();
        
        const hasActiveFilters = nombre || ficha || estado || modalidad || municipio;
        
        const filterForm = document.getElementById('filterForm');
        const filterToggle = document.querySelector('.filter-toggle__text');
        
        if (!hasActiveFilters) {
            filterForm.classList.add('hidden');
            filterToggle.textContent = '+';
        }
    });
</script>
@endsection
