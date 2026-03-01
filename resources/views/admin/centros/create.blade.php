@extends('admin.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold text-green-700 mb-8">Crear Centro</h1>
    <form action="{{ route('admin.centros.store') }}" method="POST"
            class="bg-white p-8 rounded-lg shadow max-w-xl mx-auto">
        @include('admin.centros._form')
        <div class="flex justify-end mt-6">
            <button class="bg-[#39A900] text-white px-5 py-2 rounded-lg shadow hover:bg-[#007832] transition">
                Guardar
            </button>
        </div>
    </form>
@endsection
