@extends('admin.layouts.app')

@section('title', 'Gestión de Preinscritos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-green-700">Gestión de Preinscritos</h1>
        <a href="{{ route('admin.preinscritos.create') }}"
           class="bg-[#39A900] text-white px-5 py-2 rounded-lg shadow hover:bg-[#007832] transition">
            + Nuevo Preinscrito
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Documento</th>
                    <th class="p-3 text-left">Correo</th>
                    <th class="p-3 text-left">Programa</th>
                    <th class="p-3 text-left">Estado</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($preinscritos as $preinscrito)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3">{{ $preinscrito->nombre }}</td>
                    <td class="p-3">{{ $preinscrito->documento }}</td>
                    <td class="p-3">{{ $preinscrito->correo }}</td>
                    <td class="p-3">{{ $preinscrito->ofertaPrograma->programa->nombre ?? 'N/A' }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-sm font-medium
                            {{ $preinscrito->estado === 'aceptado' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $preinscrito->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $preinscrito->estado === 'rechazado' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($preinscrito->estado) }}
                        </span>
                    </td>
                    <td class="p-3 text-right space-x-2">
                        <a href="{{ route('admin.preinscritos.show', $preinscrito) }}" class="text-blue-700 hover:underline">Ver</a>
                        <a href="{{ route('admin.preinscritos.edit', $preinscrito) }}" class="text-yellow-700 hover:underline">Editar</a>
                        <form action="{{ route('admin.preinscritos.destroy', $preinscrito) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('¿Eliminar este preinscrito?')" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">
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
