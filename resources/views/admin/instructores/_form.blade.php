<div class="admin-form__section">
    <h3 class="admin-form__section-title">Información del Instructor</h3>

    <div class="form-group">
        <label for="nombre" class="form-label">Nombre Completo *</label>
        <input
            type="text"
            name="nombre"
            id="nombre"
            value="{{ old('nombre', $instructore->nombre ?? '') }}"
            class="form-input @error('nombre') form-input--error @enderror"
            required
        >
        @error('nombre')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="perfil_descriptivo" class="form-label">Perfil Descriptivo *</label>
        <textarea
            name="perfil_descriptivo"
            id="perfil_descriptivo"
            rows="4"
            class="form-textarea @error('perfil_descriptivo') form-input--error @enderror"
            required
        >{{ old('perfil_descriptivo', $instructore->perfil_descriptivo ?? '') }}</textarea>
        <small class="form-help">Describe las competencias y áreas de especialización del instructor</small>
        @error('perfil_descriptivo')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="experiencia" class="form-label">Experiencia</label>
        <textarea
            name="experiencia"
            id="experiencia"
            rows="5"
            class="form-textarea @error('experiencia') form-input--error @enderror"
        >{{ old('experiencia', $instructore->experiencia ?? '') }}</textarea>
        <small class="form-help">Describe la trayectoria profesional y experiencia relevante (opcional)</small>
        @error('experiencia')
            <span class="form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-checkbox">
            <input
                type="checkbox"
                name="activo"
                value="1"
                {{ old('activo', $instructore->activo ?? true) ? 'checked' : '' }}
            >
            <span>Instructor activo</span>
        </label>
        <small class="form-help">Los instructores inactivos no aparecerán en la selección de ofertas</small>
    </div>
</div>
