@extends('admin.layouts.app')

@section('title', 'Editar Programa')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Editar Programa: {{ $programa->nombre }}</h1>
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
        @include('admin.programas.partials.form', ['mode' => 'edit'])
    </div>
</div>
@endsection
