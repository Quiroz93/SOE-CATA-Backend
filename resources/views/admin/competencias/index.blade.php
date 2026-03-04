@extends('admin.layouts.app')

@section('title', 'Competencias')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">Competencias</h1>
            <p class="admin-header__subtitle">Catálogo de competencias técnicas y transversales</p>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.competencias.create') }}" class="btn btn--primary">
                <span class="icon">+</span> Nueva Competencia
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif

    <!-- Tabla de Competencias -->
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Área</th>
                    <th>Estado</th>
                    <th>Programas Asociados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($competencias as $competencia)
                    <tr>
                        <td>
                            <strong>{{ $competencia->nombre }}</strong>
                            @if($competencia->descripcion)
                                <br><small class="text-muted">{{ Str::limit($competencia->descripcion, 60) }}</small>
                            @endif
                        </td>
                        <td>{{ $competencia->area ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $competencia->estado === 'publicado' ? 'badge--success' : 'badge--warning' }}">
                                {{ ucfirst($competencia->estado) }}
                            </span>
                        </td>
                        <td class="text-center">
                            {{ $competencia->programas_count }}
                        </td>
                        <td>
                            <div class="admin-table__actions">
                                <a href="{{ route('admin.competencias.show', $competencia) }}" class="btn btn--sm btn--info" title="Ver">
                                    <span class="icon">👁</span>
                                </a>
                                <a href="{{ route('admin.competencias.edit', $competencia) }}" class="btn btn--sm btn--warning" title="Editar">
                                    <span class="icon">✏️</span>
                                </a>
                                <form action="{{ route('admin.competencias.destroy', $competencia) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta competencia?')">
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
                            No se encontraron competencias
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($competencias->hasPages())
        <div class="admin-pagination">
            {{ $competencias->links('admin.pagination.default') }}
        </div>
    @endif
</div>
@endsection
