@extends('admin.layouts.app')

@section('title', 'Editar Competencia')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">Editar Competencia</h1>
            <p class="admin-header__subtitle">Modificar información de la competencia</p>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.competencias.index') }}" class="btn btn--outline">
                ← Volver al listado
            </a>
        </div>
    </div>

    <div class="admin-form-container">
        <form action="{{ route('admin.competencias.update', $competencia) }}" method="POST" class="admin-form">
            @csrf
            @method('PUT')
            @include('admin.competencias._form')

            <div class="admin-form__footer">
                <button type="submit" class="btn btn--primary">Actualizar Competencia</button>
                <a href="{{ route('admin.competencias.index') }}" class="btn btn--outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
