@extends('admin.layouts.app')

@section('title', 'Editar Inscrito')

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Editar Inscrito</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.inscritos.update', $inscrito) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.inscritos._form', ['buttonText' => 'Actualizar Inscrito'])
        </form>
    </div>
</div>
@endsection
