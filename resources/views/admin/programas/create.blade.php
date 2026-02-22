@extends('layouts.admin')
@section('title', 'Crear Programa')
@section('content')
<!-- Formulario de creación de programa -->
<div class="container mx-auto py-8">
    {{-- Título --}}
    <h1 class="text-2xl font-bold mb-4">Crear Programa</h1>
    {{-- Partial del formulario --}}
    @include('admin.programas.partials.form', ['mode' => 'create'])
</div>
@endsection
