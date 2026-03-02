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
@endsection
