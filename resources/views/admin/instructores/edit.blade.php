@extends('admin.layouts.app')

@section('title', 'Editar Instructor')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">Editar Instructor</h1>
            <p class="admin-header__subtitle">Modificar información del instructor</p>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.instructores.index') }}" class="btn btn--outline">
                ← Volver al listado
            </a>
        </div>
    </div>

    <div class="admin-form-container">
        <form action="{{ route('admin.instructores.update', $instructore) }}" method="POST" class="admin-form">
            @csrf
            @method('PUT')
            @include('admin.instructores._form')

            <div class="admin-form__footer">
                <button type="submit" class="btn btn--primary">Actualizar Instructor</button>
                <a href="{{ route('admin.instructores.index') }}" class="btn btn--outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
