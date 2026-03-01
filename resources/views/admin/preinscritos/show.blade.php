@extends('admin.layouts.app')

@section('title', 'Ver Preinscrito')

@section('content')
<div class="admin-page-container">
    <div class="admin-header">
        <h1 class="admin-header__title">Detalles del Preinscrito</h1>
        <a href="{{ route('admin.preinscritos.index') }}" class="btn btn--secondary">
            ← Volver
        </a>
    </div>

    <div class="admin-detail-card">
        <div class="admin-detail-grid">
            <div class="admin-detail-item">
                <span class="admin-detail__label">Nombre</span>
                <span class="admin-detail__value">{{ $preinscrito->nombre }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Documento</span>
                <span class="admin-detail__value">{{ $preinscrito->documento }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Correo</span>
                <span class="admin-detail__value">{{ $preinscrito->correo }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Estado</span>
                <span class="admin-detail__value">
                    <span class="badge {{ $preinscrito->estado === 'aceptado' ? 'badge--success' : '' }} {{ $preinscrito->estado === 'pendiente' ? 'badge--warning' : '' }} {{ $preinscrito->estado === 'rechazado' ? 'badge--danger' : '' }}">
                        {{ ucfirst($preinscrito->estado) }}
                    </span>
                </span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Programa</span>
                <span class="admin-detail__value">{{ $preinscrito->ofertaPrograma->programa->nombre ?? 'N/A' }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Oferta</span>
                <span class="admin-detail__value">{{ $preinscrito->ofertaPrograma->oferta->nombre ?? 'N/A' }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Fecha de Creación</span>
                <span class="admin-detail__value">{{ $preinscrito->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="admin-detail-item">
                <span class="admin-detail__label">Última Actualización</span>
                <span class="admin-detail__value">{{ $preinscrito->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <div class="admin-detail-actions">
            <a href="{{ route('admin.preinscritos.edit', $preinscrito) }}" class="btn btn--primary">
                Editar
            </a>
            <form action="{{ route('admin.preinscritos.destroy', $preinscrito) }}" method="POST" class="admin-detail__form">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('¿Eliminar este preinscrito?')" class="btn btn--danger">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
            </div>
            <div>
                <p class="text-sm text-gray-500">Última Actualización</p>
                <p class="text-lg font-medium text-gray-900">{{ $preinscrito->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('admin.preinscritos.edit', $preinscrito) }}"
           class="bg-yellow-600 text-white px-5 py-2 rounded-lg shadow hover:bg-yellow-700 transition">
            Editar
        </a>
        <form action="{{ route('admin.preinscritos.destroy', $preinscrito) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('¿Eliminar este preinscrito?')"
                    class="bg-red-600 text-white px-5 py-2 rounded-lg shadow hover:bg-red-700 transition">
                Eliminar
            </button>
        </form>
    </div>
</div>
@endsection
