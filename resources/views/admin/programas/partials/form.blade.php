<form method="POST" action="{{ isset($mode) && $mode === 'edit' ? route('admin.programas.update', $programa ?? null) : route('admin.programas.store') }}" class="admin-form">
    @csrf
    @if(isset($mode) && $mode === 'edit')
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="nombre" class="form-label">
            Nombre del Programa <span class="required">*</span>
        </label>
        <input type="text" 
               name="nombre" 
               id="nombre" 
               class="form-input @error('nombre') form-input--error @enderror" 
               value="{{ old('nombre', $programa->nombre ?? '') }}"
               required>
        @error('nombre')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="ficha" class="form-label">
            Ficha
        </label>
        <input type="text" 
               name="ficha" 
               id="ficha" 
               class="form-input @error('ficha') form-input--error @enderror" 
               value="{{ old('ficha', $programa->ficha ?? '') }}">
        @error('ficha')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="descripcion" class="form-label">
            Descripción
        </label>
        <textarea name="descripcion" 
                  id="descripcion" 
                  class="form-textarea @error('descripcion') form-textarea--error @enderror" 
                  rows="4" 
                  placeholder="Descripción del programa...">{{ old('descripcion', $programa->descripcion ?? '') }}</textarea>
        @error('descripcion')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="estado" class="form-label">
            Estado <span class="required">*</span>
        </label>
        <select name="estado" 
                id="estado" 
                class="form-select @error('estado') form-select--error @enderror" 
                required>
            <option value="">-- Selecciona un estado --</option>
            <option value="borrador" {{ old('estado', $programa->estado->value ?? '') === 'borrador' ? 'selected' : '' }}>Borrador</option>
            <option value="publicado" {{ old('estado', $programa->estado->value ?? '') === 'publicado' ? 'selected' : '' }}>Publicado</option>
            <option value="archivado" {{ old('estado', $programa->estado->value ?? '') === 'archivado' ? 'selected' : '' }}>Archivado</option>
        </select>
        @error('estado')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="modalidad" class="form-label">
            Modalidad
        </label>
        <select name="modalidad" 
                id="modalidad" 
                class="form-select @error('modalidad') form-select--error @enderror">
            <option value="">-- Selecciona una modalidad --</option>
            <option value="Presencial" {{ old('modalidad', $programa->modalidad ?? '') === 'Presencial' ? 'selected' : '' }}>Presencial</option>
            <option value="Virtual" {{ old('modalidad', $programa->modalidad ?? '') === 'Virtual' ? 'selected' : '' }}>Virtual</option>
            <option value="Mixta" {{ old('modalidad', $programa->modalidad ?? '') === 'Mixta' ? 'selected' : '' }}>Mixta</option>
        </select>
        @error('modalidad')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="municipio" class="form-label">
            Municipio
        </label>
        <input type="text" 
               name="municipio" 
               id="municipio" 
               class="form-input @error('municipio') form-input--error @enderror" 
               value="{{ old('municipio', $programa->municipio ?? '') }}"
               placeholder="Ej: Málaga">
        @error('municipio')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">
            {{ $mode === 'edit' ? '✅ Actualizar Programa' : '✅ Guardar Programa' }}
        </button>
        <a href="{{ route('admin.programas.index') }}" class="btn btn--secondary">
            ❌ Cancelar
        </a>
    </div>
</form>
