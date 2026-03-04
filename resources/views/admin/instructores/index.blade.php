@extends('admin.layouts.app')

@section('title', 'Instructores')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">Instructores</h1>
            <p class="admin-header__subtitle">Gestión de personal docente</p>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.instructores.create') }}" class="btn btn--primary">
                <span class="icon">+</span> Nuevo Instructor
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="admin-filters">
        <form method="GET" action="{{ route('admin.instructores.index') }}" class="filters-form">
            <div class="filters-form__group">
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar por nombre o perfil..."
                    class="form-input"
                >
            </div>
            <div class="filters-form__group">
                <select name="activo" class="form-select">
                    <option value="">-- Todos los Estados --</option>
                    <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="filters-form__actions">
                <button type="submit" class="btn btn--secondary">Filtrar</button>
                <a href="{{ route('admin.instructores.index') }}" class="btn btn--outline">Limpiar</a>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert--danger">{{ session('error') }}</div>
    @endif

    <!-- Tabla de Instructores -->
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>Ofertas Asignadas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($instructores as $instructor)
                    <tr>
                        <td>
                            <strong>{{ $instructor->nombre }}</strong>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 300px;">
                                {{ Str::limit($instructor->perfil_descriptivo, 80) }}
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $instructor->activo ? 'badge--success' : 'badge--danger' }}">
                                {{ $instructor->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-center">
                            {{ $instructor->ofertaProgramas->count() }}
                        </td>
                        <td>
                            <div class="admin-table__actions">
                                <a href="{{ route('admin.instructores.show', $instructor) }}" class="btn btn--sm btn--info" title="Ver">
                                    <span class="icon">👁</span>
                                </a>
                                <a href="{{ route('admin.instructores.edit', $instructor) }}" class="btn btn--sm btn--warning" title="Editar">
                                    <span class="icon">✏️</span>
                                </a>
                                <form action="{{ route('admin.instructores.destroy', $instructor) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este instructor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn--sm btn--danger" title="Eliminar">
                                        <span class="icon">🗑️</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No se encontraron instructores
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($instructores->hasPages())
        <div class="admin-pagination">
            {{ $instructores->links('admin.pagination.default') }}
        </div>
    @endif
</div>
@endsection
