@extends('layouts.admin')
@section('title', 'Editar Programa')
@section('content')
<!-- Formulario de edición de programa -->
<div class="container mx-auto py-8">
    {{-- Título --}}
    <h1 class="text-2xl font-bold mb-4">Editar Programa</h1>
    {{-- Partial del formulario --}}
    @include('admin.programas.partials.form', ['mode' => 'edit'])
</div>
@endsection
