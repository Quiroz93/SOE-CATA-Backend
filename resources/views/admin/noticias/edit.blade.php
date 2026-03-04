@extends('admin.layouts.app')

@section('title', 'Editar Noticia')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">Editar Noticia</h1>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.noticias.index') }}" class="btn btn--outline">← Volver</a>
        </div>
    </div>

    <div class="admin-form-container">
        <form action="{{ route('admin.noticias.update', $noticia) }}" method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf @method('PUT')
            @include('admin.noticias._form')
            <div class="admin-form__footer">
                <button type="submit" class="btn btn--primary">Actualizar Noticia</button>
                <a href="{{ route('admin.noticias.index') }}" class="btn btn--outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
