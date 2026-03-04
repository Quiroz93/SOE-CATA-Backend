<div class="admin-form__section">
    <h3 class="admin-form__section-title">Información de la Noticia</h3>

    <div class="form-group">
        <label for="titulo" class="form-label">Título *</label>
        <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $noticia->titulo ?? '') }}" class="form-input @error('titulo') form-input--error @enderror" required>
        @error('titulo')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label for="contenido" class="form-label">Contenido *</label>
        <textarea name="contenido" id="contenido" rows="10" class="form-textarea @error('contenido') form-input--error @enderror" required>{{ old('contenido', $noticia->contenido ?? '') }}</textarea>
        @error('contenido')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label for="fecha_publicacion" class="form-label">Fecha de Publicación</label>
        <input type="date" name="fecha_publicacion" id="fecha_publicacion" value="{{ old('fecha_publicacion', $noticia->fecha_publicacion?->format('Y-m-d') ?? '') }}" class="form-input @error('fecha_publicacion') form-input--error @enderror">
        @error('fecha_publicacion')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label for="imagen" class="form-label">Imagen</label>
        <input type="file" name="imagen" id="imagen" accept="image/*" class="form-input @error('imagen') form-input--error @enderror">
        <small class="form-help">Formatos: JPG, PNG, GIF. Máximo 2MB</small>
        @error('imagen')<span class="form-error">{{ $message }}</span>@enderror
        @if(isset($noticia) && $noticia->imagen)
            <div class="mt-2"><img src="{{ asset('storage/' . $noticia->imagen) }}" alt="Imagen actual" style="max-width: 200px; border-radius: 4px;"></div>
        @endif
    </div>

    <div class="form-group">
        <label class="form-checkbox">
            <input type="checkbox" name="publicada" value="1" {{ old('publicada', $noticia->publicada ?? false) ? 'checked' : '' }}>
            <span>Publicada</span>
        </label>
        <small class="form-help">Las noticias publicadas serán visibles en el sitio público</small>
    </div>
</div>
