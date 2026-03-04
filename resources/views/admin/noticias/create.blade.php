@extends('admin.layouts.app')

@section('title', 'Crear Noticia')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">Crear Noticia</h1>
            <p class="admin-header__subtitle">Publicar nueva noticia</p>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.noticias.index') }}" class="btn btn--outline">← Volver al listado</a>
        </div>
    </div>

    <div class="admin-form-container">
        <form action="{{ route('admin.noticias.store') }}" method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf
            @include('admin.noticias._form')
            <div class="admin-form__footer">
                <button type="submit" class="btn btn--primary">Guardar Noticia</button>
                <a href="{{ route('admin.noticias.index') }}" class="btn btn--outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
