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
                    <label for="nombre" class="filter-form__label">Nombres o Apellidos</label>
                    <input type="text" name="nombre" id="nombre" class="filter-form__input" 
                           placeholder="Buscar por nombres o apellidos..." value="{{ request('nombre') }}">
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
                        @foreach($estados as $estado)
                            <option value="{{ $estado->value }}" {{ request('estado') === $estado->value ? 'selected' : '' }}>
                                {{ $estado->label() }}
                            </option>
                        @endforeach
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
                    <th class="admin-table__th">Nombres y Apellidos</th>
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
                    <td class="admin-table__td">{{ $preinscrito->nombre_completo }}</td>
                    <td class="admin-table__td">{{ $preinscrito->documento }}</td>
                    <td class="admin-table__td">{{ $preinscrito->correo }}</td>
                    <td class="admin-table__td">{{ $preinscrito->ofertaPrograma->programa->nombre ?? 'N/A' }}</td>
                    <td class="admin-table__td">
                        @php($estadoCss = $preinscrito->estado_css_class)
                        <span class="badge {{ in_array($estadoCss, ['preinscrito', 'inscrito', 'convocado_matricula', 'matriculado'], true) ? 'badge--success' : '' }} {{ $estadoCss === 'pendiente' ? 'badge--warning' : '' }} {{ $estadoCss === 'novedad' ? 'badge--info' : '' }} {{ in_array($estadoCss, ['rechazado', 'no_admitido', 'cancelado'], true) ? 'badge--danger' : '' }}">
                            {{ $preinscrito->estado_label }}
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
