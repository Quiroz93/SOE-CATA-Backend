@extends('admin.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold text-green-700 mb-8">Editar Centro</h1>
    <form action="{{ route('admin.centros.update',$centro) }}" 
          method="POST"
          class="bg-white p-8 rounded-lg shadow max-w-xl mx-auto">
        @method('PUT')
        @include('admin.centros._form')
        <div class="flex justify-end mt-6">
            <button class="bg-yellow-600 text-white px-5 py-2 rounded-lg shadow hover:bg-yellow-700 transition">
                Actualizar
            </button>
        </div>
    </form>
@endsection
