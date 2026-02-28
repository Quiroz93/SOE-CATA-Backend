@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Detalle del Centro</h1>
    <div class="bg-white p-6 rounded-lg shadow space-y-4">
        <div>
            <strong>Nombre:</strong> {{ $centro->nombre }}
        </div>
        <div>
            <strong>Código:</strong> {{ $centro->codigo }}
        </div>
        <div>
            <strong>Dirección:</strong> {{ $centro->direccion }}
        </div>
        <div>
            <strong>Teléfono:</strong> {{ $centro->telefono }}
        </div>
        <div>
            <strong>Email:</strong> {{ $centro->email }}
        </div>
        <div>
            <strong>Estado:</strong>
            {{ $centro->estado ? 'Activo' : 'Inactivo' }}
        </div>
    </div>
@endsection
