@extends('admin.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold text-green-700 mb-8">Detalle del Centro</h1>
    <div class="bg-white p-8 rounded-lg shadow max-w-xl mx-auto space-y-6">
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
            <span class="{{ $centro->estado ? 'text-green-600' : 'text-red-600' }}">
                {{ $centro->estado ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
    </div>
@endsection
