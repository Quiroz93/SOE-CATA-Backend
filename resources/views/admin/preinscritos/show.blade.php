@extends('admin.layouts.app')

@section('title', 'Ver Preinscrito')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-green-700">Detalles del Preinscrito</h1>
        <a href="{{ route('admin.preinscritos.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Volver
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Nombre</p>
                <p class="text-lg font-medium text-gray-900">{{ $preinscrito->nombre }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Documento</p>
                <p class="text-lg font-medium text-gray-900">{{ $preinscrito->documento }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Correo</p>
                <p class="text-lg font-medium text-gray-900">{{ $preinscrito->correo }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Estado</p>
                <p class="text-lg font-medium">
                    <span class="px-2 py-1 rounded text-sm font-medium
                        {{ $preinscrito->estado === 'aceptado' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $preinscrito->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $preinscrito->estado === 'rechazado' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ ucfirst($preinscrito->estado) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Programa</p>
                <p class="text-lg font-medium text-gray-900">{{ $preinscrito->ofertaPrograma->programa->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Oferta</p>
                <p class="text-lg font-medium text-gray-900">{{ $preinscrito->ofertaPrograma->oferta->nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Fecha de Creación</p>
                <p class="text-lg font-medium text-gray-900">{{ $preinscrito->created_at->format('d/m/Y H:i') }}</p>
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
