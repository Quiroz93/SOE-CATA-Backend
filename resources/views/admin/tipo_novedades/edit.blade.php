@extends('admin.layouts.app')

@section('title', 'Editar Tipo de Novedad')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Editar Tipo de Novedad</h1>
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
        <form action="{{ route('admin.tipo-novedad.update', $tipoNovedad) }}" method="POST" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre" class="form-label">
                    Nombre <span class="required">*</span>
                </label>
                <input type="text" name="nombre" id="nombre" class="form-input @error('nombre') form-input--error @enderror" 
                       value="{{ old('nombre', $tipoNovedad->nombre) }}" required placeholder="Ej: Cambio de domicilio, Cambio de correo..."
                       maxlength="100">
                @error('nombre')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">
                    Descripción
                </label>
                <textarea name="descripcion" id="descripcion" class="form-textarea @error('descripcion') form-textarea--error @enderror" 
                          rows="4" placeholder="Describe para qué se usa este tipo de novedad..."
                          maxlength="500">{{ old('descripcion', $tipoNovedad->descripcion) }}</textarea>
                <small style="color: #666; margin-top: 0.25rem; display: block;">Máximo 500 caracteres</small>
                @error('descripcion')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary">
                    ✅ Actualizar Tipo de Novedad
                </button>
                <a href="{{ route('admin.tipo-novedad.index') }}" class="btn btn--secondary">
                    ❌ Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
