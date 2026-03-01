@extends('admin.layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-green-700">Gestión de Centros</h1>
    <a href="{{ route('admin.centros.create') }}"
       class="bg-[#39A900] text-white px-5 py-2 rounded-lg shadow hover:bg-[#007832] transition">
        + Nuevo Centro
    </a>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full">
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
                    <a href="{{ route('admin.centros.show',$centro) }}" class="text-blue-700 hover:underline">Ver</a>
                    <a href="{{ route('admin.centros.edit',$centro) }}" class="text-yellow-700 hover:underline">Editar</a>
                    <form action="{{ route('admin.centros.destroy',$centro) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('¿Eliminar este centro?')" class="text-red-600 hover:underline">Eliminar</button>
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
</div>

<div class="mt-6">
    {{ $centros->links() }}
</div>
@endsection
