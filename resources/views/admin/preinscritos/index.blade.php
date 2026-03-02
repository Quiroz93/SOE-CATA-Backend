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
@endsection
