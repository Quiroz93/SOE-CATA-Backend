@csrf

<div class="form-group">
    <label class="form-label">Oferta</label>
    @if(!empty($canManageHistoricOffers) && $canManageHistoricOffers)
        <div style="margin-bottom: 8px;">
            <button type="button" id="toggleAllOffersBtn" class="btn btn--secondary">
                Mostrar todas las ofertas
            </button>
        </div>
    @endif
    <select id="oferta_id" name="oferta_id" required class="form-select">
        <option value="">-- Seleccionar oferta --</option>
        @foreach($ofertas as $oferta)
            <option value="{{ $oferta->id }}" data-estado="{{ $oferta->estado ?? '' }}" {{ old('oferta_id', isset($preinscrito) ? $preinscrito->oferta_id : '') == $oferta->id ? 'selected' : '' }}>
                {{ $oferta->nombre }} (ID: {{ $oferta->id }}){{ ($oferta->estado ?? '') !== 'activa' ? ' - ' . ucfirst($oferta->estado ?? '') : '' }}
            </option>
        @endforeach
    </select>
    @error('oferta_id')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Programa de formación</label>
    <select id="oferta_programa_id" name="oferta_programa_id" required class="form-select">
        <option value="">-- Seleccionar programa --</option>
        @foreach($ofertasPrograma as $op)
            <option value="{{ $op->id }}" data-oferta-id="{{ $op->oferta_id }}" {{ old('oferta_programa_id', isset($preinscrito) ? $preinscrito->oferta_programa_id : '') == $op->id ? 'selected' : '' }}>
                {{ $op->programa->nombre ?? 'N/A' }}
            </option>
        @endforeach
    </select>
    @error('oferta_programa_id')
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
    <label class="form-label">Tipo de Documento <span class="required">*</span></label>
    <select name="tipo_documento" required class="form-select @error('tipo_documento') form-select--error @enderror">
        <option value="">-- Seleccionar tipo --</option>
        <option value="CC" {{ old('tipo_documento', isset($preinscrito) ? $preinscrito->tipo_documento : '') === 'CC' ? 'selected' : '' }}>CC - Cédula de Ciudadanía</option>
        <option value="TI" {{ old('tipo_documento', isset($preinscrito) ? $preinscrito->tipo_documento : '') === 'TI' ? 'selected' : '' }}>TI - Tarjeta de Identidad</option>
        <option value="CE" {{ old('tipo_documento', isset($preinscrito) ? $preinscrito->tipo_documento : '') === 'CE' ? 'selected' : '' }}>CE - Cédula de Extranjería</option>
        <option value="PAS" {{ old('tipo_documento', isset($preinscrito) ? $preinscrito->tipo_documento : '') === 'PAS' ? 'selected' : '' }}>PAS - Pasaporte</option>
        <option value="PPT" {{ old('tipo_documento', isset($preinscrito) ? $preinscrito->tipo_documento : '') === 'PPT' ? 'selected' : '' }}>PPT - Permiso de Permanencia Temporal</option>
    </select>
    @error('tipo_documento')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-label">Documento <span class="required">*</span></label>
    <input type="text" name="documento" value="{{ old('documento', isset($preinscrito) ? $preinscrito->documento : '') }}" required class="form-input" placeholder="Número de documento">
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

<div class="form-group">
    <label class="form-label" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
        <input type="checkbox" name="tiene_novedad" id="tiene_novedad" value="1" 
               {{ old('tiene_novedad') ? 'checked' : '' }}
               style="width: auto; margin: 0;">
        <span>Este preinscrito tiene una novedad que registrar</span>
    </label>
    <small style="color: #666; margin-top: 0.25rem; display: block;">
        Si marcas esta casilla, serás redirigido al formulario de novedades después de guardar el preinscrito.
    </small>
    @error('tiene_novedad')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ofertaProgramaSelect = document.getElementById('oferta_programa_id');
    const ofertaSelect = document.getElementById('oferta_id');
    const toggleAllOffersBtn = document.getElementById('toggleAllOffersBtn');
    let showAllOffers = false;

    if (!ofertaProgramaSelect || !ofertaSelect) {
        return;
    }

    const initialProgramaValue = ofertaProgramaSelect.value;

    function filterOffersByState() {
        const ofertaOptions = Array.from(ofertaSelect.querySelectorAll('option[data-estado]'));

        ofertaOptions.forEach(function (option) {
            const estado = option.getAttribute('data-estado');
            const shouldShow = showAllOffers || estado === 'activa';
            option.hidden = !shouldShow;
        });

        const selected = ofertaSelect.options[ofertaSelect.selectedIndex];
        if (selected && selected.hidden) {
            ofertaSelect.value = '';
        }

        if (toggleAllOffersBtn) {
            toggleAllOffersBtn.textContent = showAllOffers ? 'Mostrar solo ofertas vigentes' : 'Mostrar todas las ofertas';
        }
    }

    function filterProgramasByOferta(preserveSelection = false) {
        const selectedOfertaId = ofertaSelect.value;
        const currentPrograma = ofertaProgramaSelect.value;
        const programOptions = Array.from(ofertaProgramaSelect.querySelectorAll('option[data-oferta-id]'));

        programOptions.forEach(function (option) {
            const belongsToOferta = selectedOfertaId !== '' && option.getAttribute('data-oferta-id') === selectedOfertaId;
            option.hidden = !belongsToOferta;
        });

        if (preserveSelection && currentPrograma) {
            const stillValid = programOptions.some(function (option) {
                return option.value === currentPrograma && !option.hidden;
            });

            if (stillValid) {
                ofertaProgramaSelect.value = currentPrograma;
                return;
            }
        }

        ofertaProgramaSelect.value = '';
    }

    ofertaProgramaSelect.addEventListener('change', function () {
        const selectedOption = ofertaProgramaSelect.options[ofertaProgramaSelect.selectedIndex];
        const ofertaId = selectedOption ? selectedOption.getAttribute('data-oferta-id') : null;

        if (!ofertaId) {
            return;
        }

        const targetOption = ofertaSelect.querySelector('option[value="' + ofertaId + '"]');
        if (targetOption) {
            ofertaSelect.value = ofertaId;
            filterProgramasByOferta(true);
        }
    });

    ofertaSelect.addEventListener('change', function () {
        filterProgramasByOferta(false);
    });

    if (toggleAllOffersBtn) {
        toggleAllOffersBtn.addEventListener('click', function () {
            showAllOffers = !showAllOffers;
            filterOffersByState();
            filterProgramasByOferta(true);
        });
    }

    filterOffersByState();

    if (initialProgramaValue) {
        const initialProgramOption = ofertaProgramaSelect.querySelector('option[value="' + initialProgramaValue + '"]');
        if (initialProgramOption) {
            const ofertaId = initialProgramOption.getAttribute('data-oferta-id');
            if (ofertaId) {
                ofertaSelect.value = ofertaId;
            }
        }
    }

    filterProgramasByOferta(true);
});
</script>
