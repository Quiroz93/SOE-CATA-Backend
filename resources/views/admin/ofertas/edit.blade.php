@extends('layouts.admin')
@section('title', 'Editar Oferta')
@section('content')
<!-- Formulario de edición de oferta -->
<div class="container mx-auto py-8">
    {{-- Título --}}
    <h1 class="text-2xl font-bold mb-4">Editar Oferta</h1>
    {{-- Partial del formulario --}}
    @include('admin.ofertas.partials.form', ['mode' => 'edit'])
</div>
@endsection
