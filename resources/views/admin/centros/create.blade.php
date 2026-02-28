@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Crear Centro</h1>
    <form action="{{ route('admin.centros.store') }}" method="POST"
            class="bg-white p-6 rounded-lg shadow">
        @include('admin.centros._form')
        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-4 py-2 rounded-lg">
                Guardar
            </button>
        </div>
    </form>
@endsection
