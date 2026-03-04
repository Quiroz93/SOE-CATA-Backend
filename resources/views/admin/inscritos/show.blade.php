@extends('admin.layouts.app')

@section('title', 'Detalles del Inscrito')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Detalles del Inscrito</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.inscritos.edit', $inscrito) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Editar
            </a>
            <a href="{{ route('admin.inscritos.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">ID</h3>
                <p class="text-lg">{{ $inscrito->id }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Estado</h3>
                <span class="px-3 py-1 text-sm rounded-full
                    @if($inscrito->estado === 'inscrito') bg-blue-100 text-blue-800
                    @elseif($inscrito->estado === 'matriculado') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($inscrito->estado) }}
                </span>
            </div>
        </div>

        <hr>

        <div>
            <h2 class="text-xl font-semibold mb-4">Información del Preinscrito</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Nombre Completo</h3>
                    <p class="text-lg">{{ $inscrito->preinscrito->nombres }} {{ $inscrito->preinscrito->apellidos }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Documento</h3>
                    <p class="text-lg">{{ $inscrito->preinscrito->tipo_documento }}: {{ $inscrito->preinscrito->documento }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Correo</h3>
                    <p class="text-lg">{{ $inscrito->preinscrito->correo }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Estado Preinscripción</h3>
                    <p class="text-lg">{{ $inscrito->preinscrito->estado }}</p>
                </div>
            </div>
        </div>

        <hr>

        <div>
            <h2 class="text-xl font-semibold mb-4">Información del Programa</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Programa</h3>
                    <p class="text-lg">{{ $inscrito->programa->nombre }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Código Programa</h3>
                    <p class="text-lg">{{ $inscrito->programa->codigo ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <hr>

        <div>
            <h2 class="text-xl font-semibold mb-4">Información de la Oferta</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Oferta</h3>
                    <p class="text-lg">{{ $inscrito->oferta->nombre }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Cupos Disponibles</h3>
                    <p class="text-lg">{{ $inscrito->oferta->cupos ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <hr>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Fecha de Inscripción</h3>
                <p class="text-lg">{{ $inscrito->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Última Actualización</h3>
                <p class="text-lg">{{ $inscrito->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
