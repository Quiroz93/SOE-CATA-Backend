@extends('layouts.admin')
@section('title', 'Ofertas - Administración')
@section('content')
<!-- Listado de ofertas -->
<div class="container mx-auto py-8">
    {{-- Sección filtros --}}
    {{-- Sección listado de ofertas --}}
    {{-- Botón para crear nueva oferta --}}
    <a href="{{ route('admin.ofertas.create') }}" class="btn btn-primary">Nueva Oferta</a>
    {{-- Punto de montaje Vue para filtros avanzados --}}
    <div id="vue-ofertas-filtros"></div>
</div>
@endsection
