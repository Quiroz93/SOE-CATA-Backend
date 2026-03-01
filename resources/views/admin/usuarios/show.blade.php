@extends('admin.layouts.app')

@section('content')
@can('view', $usuario)
    <h1 class="text-3xl font-bold text-[#00304D] mb-8">Detalle del Usuario</h1>
    <div class="bg-white p-8 rounded-lg shadow max-w-xl mx-auto space-y-6">
        <div>
            <strong>Nombre:</strong> {{ $usuario->name }}
        </div>
        <div>
            <strong>Email:</strong> {{ $usuario->email }}
        </div>
        <div>
            <strong>Creado:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}
        </div>
    </div>
@endcan
@endsection
