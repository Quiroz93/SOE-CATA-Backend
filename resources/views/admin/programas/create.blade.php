@extends('admin.layouts.app')

@section('title', 'Crear Programa')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Crear Nuevo Programa</h1>
    </div>

    @if($errors->any())
        <div class="alert alert--danger">
            <strong>⚠️ Por favor corrige los siguientes errores:</strong>
            <ul style="margin-top: 0.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-form-card">
        @include('admin.programas.partials.form', ['mode' => 'create'])
    </div>
</div>
@endsection
