@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Editar Centro</h1>
    <form action="{{ route('admin.centros.update',$centro) }}" 
          method="POST"
          class="bg-white p-6 rounded-lg shadow">
        @method('PUT')
        @include('admin.centros._form')
        <div class="flex justify-end">
            <button class="bg-yellow-600 text-white px-4 py-2 rounded-lg">
                Actualizar
            </button>
        </div>
    </form>
@endsection
