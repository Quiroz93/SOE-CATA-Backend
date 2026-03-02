@csrf

<div class="form-group">
    <label class="form-label">Oferta Programa</label>
    <select name="oferta_programa_id" required class="form-select">
        <option value="">-- Seleccionar --</option>
        @foreach($ofertasPrograma as $op)
            <option value="{{ $op->id }}" {{ old('oferta_programa_id', isset($preinscrito) ? $preinscrito->oferta_programa_id : '') == $op->id ? 'selected' : '' }}>
                {{ $op->oferta->nombre ?? 'N/A' }} - {{ $op->programa->nombre ?? 'N/A' }}
            </option>
        @endforeach
    </select>
    @error('oferta_programa_id')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">ID Oferta</label>
    <input type="number" name="oferta_id" value="{{ old('oferta_id', isset($preinscrito) ? $preinscrito->oferta_id : '') }}" required class="form-input">
    @error('oferta_id')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Nombres</label>
    <input type="text" name="nombre" value="{{ old('nombre', isset($preinscrito) ? $preinscrito->nombre : '') }}" required class="form-input" maxlength="100" placeholder="Ej: Juan Carlos, María Isabel">
    @error('nombre')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Apellidos</label>
    <input type="text" name="apellido" value="{{ old('apellido', isset($preinscrito) ? $preinscrito->apellido : '') }}" required class="form-input" maxlength="100" placeholder="Ej: García López, Martínez Silva">
    @error('apellido')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Documento</label>
    <input type="text" name="documento" value="{{ old('documento', isset($preinscrito) ? $preinscrito->documento : '') }}" required class="form-input">
    @error('documento')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Correo</label>
    <input type="email" name="correo" value="{{ old('correo', isset($preinscrito) ? $preinscrito->correo : '') }}" required class="form-input">
    @error('correo')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Estado</label>
    <select name="estado" required class="form-select">
        <option value="pendiente" {{ old('estado', isset($preinscrito) ? $preinscrito->estado : 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        <option value="novedad" {{ old('estado', isset($preinscrito) ? $preinscrito->estado : '') == 'novedad' ? 'selected' : '' }}>Novedad</option>
        <option value="preinscrito" {{ old('estado', isset($preinscrito) ? $preinscrito->estado : '') == 'preinscrito' ? 'selected' : '' }}>Preinscrito</option>
        <option value="inscrito" {{ old('estado', isset($preinscrito) ? $preinscrito->estado : '') == 'inscrito' ? 'selected' : '' }}>Inscrito</option>
        <option value="rechazado" {{ old('estado', isset($preinscrito) ? $preinscrito->estado : '') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
    </select>
    @error('estado')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>
