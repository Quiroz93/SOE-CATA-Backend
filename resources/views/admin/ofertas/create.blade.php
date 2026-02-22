@extends('layouts.admin')
@section('title', 'Crear Oferta')
@section('content')
<!-- Formulario de creación de oferta -->
<div class="container mx-auto py-8">
    {{-- Título --}}
    <h1 class="text-2xl font-bold mb-4">Crear Oferta</h1>
    {{-- Partial del formulario --}}
    @include('admin.ofertas.partials.form', ['mode' => 'create'])
</div>
@endsection
