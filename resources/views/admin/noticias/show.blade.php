@extends('admin.layouts.app')

@section('title', 'Ver Noticia')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">{{ $noticia->titulo }}</h1>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.noticias.edit', $noticia) }}" class="btn btn--warning">✏️ Editar</a>
            <a href="{{ route('admin.noticias.index') }}" class="btn btn--outline">← Volver</a>
        </div>
    </div>

    <div class="admin-details">
        <div class="admin-details__section">
            <h3 class="admin-details__section-title">Información General</h3>
            <dl class="admin-details__list">
                <div class="admin-details__item"><dt>Estado:</dt><dd><span class="badge {{ $noticia->publicada ? 'badge--success' : 'badge--warning' }}">{{ $noticia->publicada ? 'Publicada' : 'Borrador' }}</span></dd></div>
                <div class="admin-details__item"><dt>Fecha de Publicación:</dt><dd>{{ $noticia->fecha_publicacion?->format('d/m/Y') ?? 'Sin fecha' }}</dd></div>
                <div class="admin-details__item"><dt>Creada:</dt><dd>{{ $noticia->created_at->format('d/m/Y H:i') }}</dd></div>
                @if($noticia->updated_at != $noticia->created_at)
                <div class="admin-details__item"><dt>Actualizada:</dt><dd>{{ $noticia->updated_at->format('d/m/Y H:i') }}</dd></div>
                @endif
            </dl>
        </div>

        @if($noticia->imagen)
        <div class="admin-details__section">
            <h3 class="admin-details__section-title">Imagen</h3>
            <img src="{{ asset('storage/' . $noticia->imagen) }}" alt="{{ $noticia->titulo }}" style="max-width: 100%; border-radius: 8px;">
        </div>
        @endif

        <div class="admin-details__section">
            <h3 class="admin-details__section-title">Contenido</h3>
            <div class="admin-details__content">
                <p style="white-space: pre-line;">{{ $noticia->contenido }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
