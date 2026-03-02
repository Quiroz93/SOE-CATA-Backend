@extends('admin.layouts.app')

@section('title', 'Editar Novedad')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Editar Novedad</h1>
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
        <form action="{{ route('admin.novedades.update', $novedad) }}" method="POST" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="preinscrito_id" class="form-label">
                    Preinscrito <span class="required">*</span>
                </label>
                <select name="preinscrito_id" id="preinscrito_id" class="form-select @error('preinscrito_id') form-select--error @enderror" required>
                    <option value="">-- Selecciona un preinscrito --</option>
                    @foreach($preinscritos as $preinscrito)
                        <option value="{{ $preinscrito->id }}" {{ old('preinscrito_id', $novedad->preinscrito_id) == $preinscrito->id ? 'selected' : '' }}>
                            {{ $preinscrito->nombre }} ({{ $preinscrito->documento }})
                        </option>
                    @endforeach
                </select>
                @error('preinscrito_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="tipo_novedad_id" class="form-label">
                    Tipo de Novedad <span class="required">*</span>
                </label>
                <select name="tipo_novedad_id" id="tipo_novedad_id" class="form-select @error('tipo_novedad_id') form-select--error @enderror" required>
                    <option value="">-- Selecciona un tipo de novedad --</option>
                    @foreach($tiposNovedad as $tipo)
                        <option value="{{ $tipo->id }}" {{ old('tipo_novedad_id', $novedad->tipo_novedad_id) == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_novedad_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="detalle" class="form-label">
                    Detalle
                </label>
                <textarea name="detalle" id="detalle" class="form-textarea @error('detalle') form-textarea--error @enderror" rows="4" placeholder="Ingresa los detalles de la novedad...">{{ old('detalle', $novedad->detalle) }}</textarea>
                @error('detalle')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary">
                    ✅ Actualizar Novedad
                </button>
                <a href="{{ route('admin.novedades.index') }}" class="btn btn--secondary">
                    ❌ Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
