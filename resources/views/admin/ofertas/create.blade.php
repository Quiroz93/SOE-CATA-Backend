@extends('admin.layouts.app')

@section('title', 'Crear Oferta')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Crear Nueva Oferta</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.ofertas.index') }}" class="btn btn--secondary">
                ← Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert--danger">
            <strong>Errores en el formulario:</strong>
            <ul style="margin: 0.5rem 0 0 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('admin.ofertas.partials.form', ['mode' => 'create', 'oferta' => null])
</div>
@endsection
