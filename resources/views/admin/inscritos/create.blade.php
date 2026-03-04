@extends('admin.layouts.app')

@section('title', 'Crear Inscrito')

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Crear Inscrito</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.inscritos.store') }}" method="POST">
            @csrf
            @include('admin.inscritos._form', ['buttonText' => 'Crear Inscrito'])
        </form>
    </div>
</div>
@endsection
