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
    <label class="form-label">Oferta</label>
    <select name="oferta_id" required class="form-select">
        <option value="">-- Seleccionar oferta --</option>
        @foreach($ofertas as $oferta)
            <option value="{{ $oferta->id }}" {{ old('oferta_id', isset($preinscrito) ? $preinscrito->oferta_id : '') == $oferta->id ? 'selected' : '' }}>
                {{ $oferta->nombre }} (ID: {{ $oferta->id }})
            </option>
        @endforeach
    </select>
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
    @php
        $estadoActual = old('estado', isset($preinscrito) ? $preinscrito->estado_valor : (\App\Domain\Programa\Enums\EstadoPreinscrito::tryFromInput('pendiente')?->value ?? null));
    @endphp
    <select name="estado" required class="form-select">
        @foreach($estados as $estado)
            <option value="{{ $estado->value }}" {{ $estadoActual === $estado->value ? 'selected' : '' }}>
                {{ $estado->label() }}
            </option>
        @endforeach
    </select>
    @error('estado')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>
