@extends('admin.layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-[#00304D]">Gestión de Usuarios</h1>
    @can('create', App\Models\User::class)
    <a href="{{ route('admin.usuarios.create') }}"
       class="bg-[#39A900] text-white px-5 py-2 rounded-lg shadow hover:bg-[#007832] transition">
        + Nuevo Usuario
    </a>
    @endcan
</div>
<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Nombre</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Creado</th>
                <th class="p-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
            <tr class="border-t">
                <td class="p-3">{{ $usuario->name }}</td>
                <td class="p-3">{{ $usuario->email }}</td>
                <td class="p-3">{{ $usuario->created_at->format('d/m/Y') }}</td>
                <td class="p-3 text-right space-x-2">
                    @can('view', $usuario)
                    <a href="{{ route('admin.usuarios.show',$usuario) }}" class="text-blue-700 hover:underline">Ver</a>
                    @endcan
                    @can('update', $usuario)
                    <a href="{{ route('admin.usuarios.edit',$usuario) }}" class="text-yellow-700 hover:underline">Editar</a>
                    @endcan
                    @can('delete', $usuario)
                    <form action="{{ route('admin.usuarios.destroy',$usuario) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('¿Eliminar este usuario?')" class="text-red-600 hover:underline">Eliminar</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="p-4 text-center text-gray-500">
                    No hay usuarios registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">
    {{ $usuarios->links() }}
</div>
@endsection
