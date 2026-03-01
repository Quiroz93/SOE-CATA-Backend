@extends('admin.layouts.app')

@section('title', 'Gestión de Centros')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Gestión de Centros</h1>
        <a href="{{ route('admin.centros.create') }}" class="btn btn--primary">
            + Nuevo Centro
        </a>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead class="admin-table__head">
                <tr class="admin-table__head-row">
                    <th class="admin-table__th">Nombre</th>
                    <th class="admin-table__th">Código</th>
                    <th class="admin-table__th">Estado</th>
                    <th class="admin-table__th admin-table__th--right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centros as $centro)
                <tr class="admin-table__body-row">
                    <td class="admin-table__td">{{ $centro->nombre }}</td>
                    <td class="admin-table__td">{{ $centro->codigo }}</td>
                    <td class="admin-table__td">
                        @if($centro->estado)
                            <span class="badge badge--success">Activo</span>
                        @else
                            <span class="badge badge--danger">Inactivo</span>
                        @endif
                    </td>
                    <td class="admin-table__td admin-table__td--right">
                        <div class="admin-table__actions">
                            <a href="{{ route('admin.centros.show',$centro) }}" class="admin-table__action-link">Ver</a>
                            <a href="{{ route('admin.centros.edit',$centro) }}" class="admin-table__action-link admin-table__action-link--edit">Editar</a>
                            <form action="{{ route('admin.centros.destroy',$centro) }}" method="POST" class="admin-table__action-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Eliminar este centro?')" class="admin-table__action-btn">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="admin-table__empty">
                        No hay centros registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $centros->links() }}
    </div>
</div>
@endsection
