@extends('admin.layouts.app')

@section('title', 'Editar Preinscrito')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-green-700 mb-8">Editar Preinscrito</h1>
    
    <form action="{{ route('admin.preinscritos.update', $preinscrito) }}" method="POST"
          class="bg-white p-8 rounded-lg shadow">
        @csrf
        @method('PUT')
        
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('admin.preinscritos._form')
        
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.preinscritos.index') }}"
               class="bg-gray-500 text-white px-5 py-2 rounded-lg shadow hover:bg-gray-600 transition">
                Cancelar
            </a>
            <button type="submit" class="bg-yellow-600 text-white px-5 py-2 rounded-lg shadow hover:bg-yellow-700 transition">
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection
