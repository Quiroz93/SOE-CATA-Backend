@extends('admin.layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Centros</h1>
    <a href="{{ route('admin.centros.create') }}"
       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
        + Nuevo Centro
    </a>
</div>

<table class="w-full bg-white shadow rounded-lg overflow-hidden">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Nombre</th>
            <th class="p-3 text-left">Código</th>
            <th class="p-3 text-left">Estado</th>
            <th class="p-3 text-right">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($centros as $centro)
        <tr class="border-t">
            <td class="p-3">{{ $centro->nombre }}</td>
            <td class="p-3">{{ $centro->codigo }}</td>
            <td class="p-3">
                @if($centro->estado)
                    <span class="text-green-600">Activo</span>
                @else
                    <span class="text-red-600">Inactivo</span>
                @endif
            </td>
            <td class="p-3 text-right space-x-2">
                <a href="{{ route('admin.centros.show',$centro) }}" class="text-blue-600">Ver</a>
                <a href="{{ route('admin.centros.edit',$centro) }}" class="text-yellow-600">Editar</a>
                <form action="{{ route('admin.centros.destroy',$centro) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('¿Eliminar este centro?')" class="text-red-600">Eliminar</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="p-4 text-center text-gray-500">
                No hay centros registrados.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $centros->links() }}
</div>
@endsection
