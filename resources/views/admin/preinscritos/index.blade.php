@extends('admin.layouts.app')

@section('title', 'Gestión de Preinscritos')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Gestión de Preinscritos</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.preinscritos.create') }}" class="btn btn--primary">
                + Nuevo Preinscrito
            </a>
            <a href="{{ route('admin.preinscritos.downloadTemplate') }}" class="btn btn--secondary" title="Descargar plantilla Excel">
                📋 Plantilla Excel
            </a>
            <a href="{{ route('admin.preinscritos.showImportForm') }}" class="btn btn--secondary" title="Importar desde Excel">
                📤 Importar
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

        <form method="GET" action="{{ route('admin.preinscritos.index') }}" class="filter-form" id="filterForm">
            <div class="filter-form__grid">
                <div class="filter-form__group">
                    <label for="nombre" class="filter-form__label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="filter-form__input" 
                           placeholder="Buscar por nombre..." value="{{ request('nombre') }}">
                </div>

                <div class="filter-form__group">
                    <label for="documento" class="filter-form__label">Documento</label>
                    <input type="text" name="documento" id="documento" class="filter-form__input" 
                           placeholder="Buscar por documento..." value="{{ request('documento') }}">
                </div>

                <div class="filter-form__group">
                    <label for="correo" class="filter-form__label">Correo</label>
                    <input type="email" name="correo" id="correo" class="filter-form__input" 
                           placeholder="Buscar por correo..." value="{{ request('correo') }}">
                </div>

                <div class="filter-form__group">
                    <label for="programa_id" class="filter-form__label">Programa</label>
                    <select name="programa_id" id="programa_id" class="filter-form__select">
                        <option value="">-- Todos los Programas --</option>
                        @foreach($programas as $programa)
                            <option value="{{ $programa->id }}" 
                                {{ request('programa_id') == $programa->id ? 'selected' : '' }}>
                                {{ $programa->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-form__group">
                    <label for="estado" class="filter-form__label">Estado</label>
                    <select name="estado" id="estado" class="filter-form__select">
                        <option value="">-- Todos los Estados --</option>
                        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>
                            Pendiente
                        </option>
                        <option value="aceptado" {{ request('estado') === 'aceptado' ? 'selected' : '' }}>
                            Aceptado
                        </option>
                        <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>
                            Rechazado
                        </option>
                    </select>
                </div>
            </div>

            <div class="filter-form__actions">
                <button type="submit" class="btn btn--primary">
                    🔍 Buscar
                </button>
                <a href="{{ route('admin.preinscritos.index') }}" class="btn btn--secondary">
                    ↻ Limpiar Filtros
                </a>
            </div>
        </form>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead class="admin-table__head">
                <tr class="admin-table__head-row">
                    <th class="admin-table__th">Nombre</th>
                    <th class="admin-table__th">Documento</th>
                    <th class="admin-table__th">Correo</th>
                    <th class="admin-table__th">Programa</th>
                    <th class="admin-table__th">Estado</th>
                    <th class="admin-table__th admin-table__th--right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($preinscritos as $preinscrito)
                <tr class="admin-table__body-row">
                    <td class="admin-table__td">{{ $preinscrito->nombre }}</td>
                    <td class="admin-table__td">{{ $preinscrito->documento }}</td>
                    <td class="admin-table__td">{{ $preinscrito->correo }}</td>
                    <td class="admin-table__td">{{ $preinscrito->ofertaPrograma->programa->nombre ?? 'N/A' }}</td>
                    <td class="admin-table__td">
                        <span class="badge {{ $preinscrito->estado === 'aceptado' ? 'badge--success' : '' }} {{ $preinscrito->estado === 'pendiente' ? 'badge--warning' : '' }} {{ $preinscrito->estado === 'rechazado' ? 'badge--danger' : '' }}">
                            {{ ucfirst($preinscrito->estado) }}
                        </span>
                    </td>
                    <td class="admin-table__td admin-table__td--right">
                        <div class="admin-table__actions">
                            <a href="{{ route('admin.preinscritos.show', $preinscrito) }}" class="admin-table__action-link">Ver</a>
                            <a href="{{ route('admin.preinscritos.edit', $preinscrito) }}" class="admin-table__action-link admin-table__action-link--edit">Editar</a>
                            <form action="{{ route('admin.preinscritos.destroy', $preinscrito) }}" method="POST" class="admin-table__action-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Eliminar este preinscrito?')" class="admin-table__action-btn">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="admin-table__empty">
                        No hay preinscritos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $preinscritos->links() }}
    </div>
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
        const nombre = document.getElementById('nombre').value.trim();
        const documento = document.getElementById('documento').value.trim();
        const correo = document.getElementById('correo').value.trim();
        const programaId = document.getElementById('programa_id').value.trim();
        const estado = document.getElementById('estado').value.trim();
        
        const hasActiveFilters = nombre || documento || correo || programaId || estado;
        
        const filterForm = document.getElementById('filterForm');
        const filterToggle = document.querySelector('.filter-toggle__text');
        
        if (!hasActiveFilters) {
            filterForm.classList.add('hidden');
            filterToggle.textContent = '+';
        }
    });
</script>
@endsection
