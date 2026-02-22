@extends('layouts.admin')
@section('title', 'Programas - Administración')
@section('content')
<!-- Listado de programas -->
<div class="container mx-auto py-8">
    {{-- Sección filtros --}}
    {{-- Sección listado de programas --}}
    {{-- Botón para crear nuevo programa --}}
    <a href="{{ route('admin.programas.create') }}" class="btn btn-primary">Nuevo Programa</a>
    {{-- Punto de montaje Vue para filtros avanzados --}}
    <div id="vue-programas-filtros"></div>
</div>
@endsection
