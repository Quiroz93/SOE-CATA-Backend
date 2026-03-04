@extends('admin.layouts.app')

@section('title', 'Crear Competencia')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">Crear Competencia</h1>
            <p class="admin-header__subtitle">Registrar nueva competencia en el catálogo</p>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.competencias.index') }}" class="btn btn--outline">
                ← Volver al listado
            </a>
        </div>
    </div>

    <div class="admin-form-container">
        <form action="{{ route('admin.competencias.store') }}" method="POST" class="admin-form">
            @csrf
            @include('admin.competencias._form')

            <div class="admin-form__footer">
                <button type="submit" class="btn btn--primary">Guardar Competencia</button>
                <a href="{{ route('admin.competencias.index') }}" class="btn btn--outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
