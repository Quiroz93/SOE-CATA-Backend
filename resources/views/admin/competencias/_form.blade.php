<div class="admin-form__section">
    <h3 class="admin-form__section-title">Información de la Competencia</h3>

    <div class="form-group">
        <label for="nombre" class="form-label">Nombre de la Competencia *</label>
        <input
            type="text"
            name="nombre"
            id="nombre"
            value="{{ old('nombre', $competencia->nombre ?? '') }}"
            class="form-input @error('nombre') form-input--error @enderror"
            required
        >
        @error('nombre')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="area" class="form-label">Área</label>
        <input
            type="text"
            name="area"
            id="area"
            value="{{ old('area', $competencia->area ?? '') }}"
            class="form-input @error('area') form-input--error @enderror"
            placeholder="Ej: Técnica, Transversal, Específica"
        >
        <small class="form-help">Área o clasificación de la competencia (opcional)</small>
        @error('area')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea
            name="descripcion"
            id="descripcion"
            rows="4"
            class="form-textarea @error('descripcion') form-input--error @enderror"
        >{{ old('descripcion', $competencia->descripcion ?? '') }}</textarea>
        <small class="form-help">Describe los alcances y objetivos de la competencia (opcional)</small>
        @error('descripcion')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="estado" class="form-label">Estado *</label>
        <select
            name="estado"
            id="estado"
            class="form-select @error('estado') form-input--error @enderror"
            required
        >
            <option value="">-- Seleccionar Estado --</option>
            <option value="publicado" {{ old('estado', $competencia->estado ?? 'publicado') === 'publicado' ? 'selected' : '' }}>
                Publicado
            </option>
            <option value="borrador" {{ old('estado', $competencia->estado ?? '') === 'borrador' ? 'selected' : '' }}>
                Borrador
            </option>
        </select>
        <small class="form-help">Las competencias publicadas estarán disponibles para vincular a programas</small>
        @error('estado')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>
</div>
