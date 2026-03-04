@extends('admin.layouts.app')

@section('title', 'Inscritos')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Inscritos</h1>
        <a href="{{ route('admin.inscritos.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Crear Inscrito
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.inscritos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Buscar por nombre..."
                    class="w-full px-3 py-2 border rounded-lg"
                >
            </div>
            <div>
                <select name="estado" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">Todos los estados</option>
                    <option value="inscrito" {{ request('estado') === 'inscrito' ? 'selected' : '' }}>Inscrito</option>
                    <option value="matriculado" {{ request('estado') === 'matriculado' ? 'selected' : '' }}>Matriculado</option>
                    <option value="retirado" {{ request('estado') === 'retirado' ? 'selected' : '' }}>Retirado</option>
                </select>
            </div>
            <div>
                <select name="oferta_id" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">Todas las ofertas</option>
                    @foreach($ofertas as $oferta)
                        <option value="{{ $oferta->id }}" {{ request('oferta_id') == $oferta->id ? 'selected' : '' }}>
                            {{ $oferta->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Buscar
                </button>
                <a href="{{ route('admin.inscritos.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Preinscrito</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Oferta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($inscritos as $inscrito)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $inscrito->id }}</td>
                        <td class="px-6 py-4">
                            {{ $inscrito->preinscrito->nombres }} {{ $inscrito->preinscrito->apellidos }}
                        </td>
                        <td class="px-6 py-4">{{ $inscrito->programa->nombre }}</td>
                        <td class="px-6 py-4">{{ $inscrito->oferta->nombre }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($inscrito->estado === 'inscrito') bg-blue-100 text-blue-800
                                @elseif($inscrito->estado === 'matriculado') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($inscrito->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.inscritos.show', $inscrito) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                            <a href="{{ route('admin.inscritos.edit', $inscrito) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                            <form action="{{ route('admin.inscritos.destroy', $inscrito) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No se encontraron inscritos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $inscritos->links() }}
    </div>
</div>
@endsection
