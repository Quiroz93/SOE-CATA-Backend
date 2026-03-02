{{-- Formulario reutilizable para crear/editar oferta --}}
<form id="ofertaForm" method="POST" action="{{ $mode === 'edit' ? route('admin.ofertas.update', $oferta) : route('admin.ofertas.store') }}" class="admin-form">
    @csrf
    @if($mode === 'edit')
        @method('PUT')
    @endif

    <div class="admin-form-card">
        <h2 class="admin-form-card__title">Información General</h2>
        
        <div class="form-grid">
            {{-- Nombre de la oferta --}}
            <div class="form-group">
                <label for="nombre" class="form-label required">Nombre de la Oferta</label>
                <input 
                    type="text" 
                    name="nombre" 
                    id="nombre" 
                    class="form-input @error('nombre') form-input--error @enderror" 
                    value="{{ old('nombre', $oferta->nombre ?? '') }}"
                    required
                    placeholder="Ej: Convocatoria 2026-1">
                @error('nombre')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Centro --}}
            <div class="form-group">
                <label for="centro_id" class="form-label required">Centro</label>
                <select 
                    name="centro_id" 
                    id="centro_id" 
                    class="form-select @error('centro_id') form-input--error @enderror"
                    required>
                    <option value="">-- Seleccione un Centro --</option>
                    @foreach($centros as $centro)
                        <option value="{{ $centro->id }}" {{ old('centro_id', $oferta->centro_id ?? '') == $centro->id ? 'selected' : '' }}>
                            {{ $centro->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('centro_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Estado --}}
            <div class="form-group">
                <label for="estado" class="form-label required">Estado</label>
                <select 
                    name="estado" 
                    id="estado" 
                    class="form-select @error('estado') form-input--error @enderror"
                    required>
                    <option value="activa" {{ old('estado', $oferta->estado ?? 'activa') === 'activa' ? 'selected' : '' }}>Activa</option>
                    <option value="inactiva" {{ old('estado', $oferta->estado ?? '') === 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                    <option value="vencida" {{ old('estado', $oferta->estado ?? '') === 'vencida' ? 'selected' : '' }}>Vencida</option>
                </select>
                @error('estado')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Fecha de Inicio --}}
            <div class="form-group">
                <label for="fecha_inicio" class="form-label required">Fecha de Inicio</label>
                <input 
                    type="date" 
                    name="fecha_inicio" 
                    id="fecha_inicio" 
                    class="form-input @error('fecha_inicio') form-input--error @enderror" 
                    value="{{ old('fecha_inicio', $oferta ? $oferta->fecha_inicio->format('Y-m-d') : '') }}"
                    required>
                @error('fecha_inicio')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Fecha de Fin --}}
            <div class="form-group">
                <label for="fecha_fin" class="form-label required">Fecha de Fin</label>
                <input 
                    type="date" 
                    name="fecha_fin" 
                    id="fecha_fin" 
                    class="form-input @error('fecha_fin') form-input--error @enderror" 
                    value="{{ old('fecha_fin', $oferta ? $oferta->fecha_fin->format('Y-m-d') : '') }}"
                    required>
                @error('fecha_fin')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Descripción --}}
        <div class="form-group">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea 
                name="descripcion" 
                id="descripcion" 
                class="form-textarea @error('descripcion') form-input--error @enderror" 
                rows="4"
                placeholder="Descripción detallada de la oferta...">{{ old('descripcion', $oferta->descripcion ?? '') }}</textarea>
            @error('descripcion')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Sección de Programas --}}
    <div class="admin-form-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 class="admin-form-card__title" style="margin: 0;">Programas Asociados</h2>
            <button type="button" class="btn btn--sm btn--primary" onclick="addPrograma()">
                + Agregar Programa
            </button>
        </div>

        <div id="programasContainer">
            @if($mode === 'edit' && $oferta && $oferta->ofertaProgramas->count() > 0)
                @foreach($oferta->ofertaProgramas as $index => $ofertaPrograma)
                    <div class="programa-row" data-index="{{ $index }}">
                        <input type="hidden" name="programas[{{ $index }}][id]" value="{{ $ofertaPrograma->id }}">
                        
                        <div class="programa-grid">
                            <div class="form-group">
                                <label class="form-label required">Programa</label>
                                <select name="programas[{{ $index }}][programa_id]" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($programas as $programa)
                                        <option value="{{ $programa->id }}" {{ $ofertaPrograma->programa_id == $programa->id ? 'selected' : '' }}>
                                            {{ $programa->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Centro</label>
                                <select name="programas[{{ $index }}][centro_id]" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($centros as $centro)
                                        <option value="{{ $centro->id }}" {{ $ofertaPrograma->centro_id == $centro->id ? 'selected' : '' }}>
                                            {{ $centro->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Instructor</label>
                                <select name="programas[{{ $index }}][instructor_id]" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($instructores as $instructor)
                                        <option value="{{ $instructor->id }}" {{ $ofertaPrograma->instructor_id == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Cupos</label>
                                <input type="number" name="programas[{{ $index }}][cupos]" class="form-input" value="{{ $ofertaPrograma->cupos }}" min="1" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Modalidad</label>
                                <select name="programas[{{ $index }}][modalidad]" class="form-select">
                                    <option value="">-- Seleccione --</option>
                                    <option value="Presencial" {{ $ofertaPrograma->modalidad === 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                    <option value="Virtual" {{ $ofertaPrograma->modalidad === 'Virtual' ? 'selected' : '' }}>Virtual</option>
                                    <option value="Mixta" {{ $ofertaPrograma->modalidad === 'Mixta' ? 'selected' : '' }}>Mixta</option>
                                </select>
                            </div>

                            <div class="form-group" style="display: flex; align-items: flex-end;">
                                <button type="button" class="btn btn--sm btn--danger" onclick="removePrograma(this)">
                                    🗑️ Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted" id="noProgramasMessage">No hay programas agregados. Haga clic en "Agregar Programa".</p>
            @endif
        </div>
    </div>

    {{-- Acciones del formulario --}}
    <div class="form-actions">
        <button type="submit" class="btn btn--primary">
            {{ $mode === 'edit' ? '💾 Actualizar Oferta' : '✨ Crear Oferta' }}
        </button>
        <a href="{{ route('admin.ofertas.index') }}" class="btn btn--secondary">
            ❌ Cancelar
        </a>
    </div>
</form>

@vite(['resources/css/admin-crud.css'])

<style>
.programa-row {
    background: #f9f9f9;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border: 1px solid #e0e0e0;
}

.programa-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.text-muted {
    color: #666;
    font-style: italic;
    padding: 1rem;
    text-align: center;
}
</style>

<script>
let programaIndex = {{ $mode === 'edit' && $oferta ? $oferta->ofertaProgramas->count() : 0 }};

const centrosData = @json($centros);
const programasData = @json($programas);
const instructoresData = @json($instructores);

function addPrograma() {
    const container = document.getElementById('programasContainer');
    const message = document.getElementById('noProgramasMessage');
    if (message) message.remove();

    const programaRow = document.createElement('div');
    programaRow.className = 'programa-row';
    programaRow.setAttribute('data-index', programaIndex);

    programaRow.innerHTML = `
        <div class="programa-grid">
            <div class="form-group">
                <label class="form-label required">Programa</label>
                <select name="programas[${programaIndex}][programa_id]" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    ${programasData.map(p => `<option value="${p.id}">${p.nombre}</option>`).join('')}
                </select>
            </div>

            <div class="form-group">
                <label class="form-label required">Centro</label>
                <select name="programas[${programaIndex}][centro_id]" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    ${centrosData.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('')}
                </select>
            </div>

            <div class="form-group">
                <label class="form-label required">Instructor</label>
                <select name="programas[${programaIndex}][instructor_id]" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    ${instructoresData.map(i => `<option value="${i.id}">${i.nombre}</option>`).join('')}
                </select>
            </div>

            <div class="form-group">
                <label class="form-label required">Cupos</label>
                <input type="number" name="programas[${programaIndex}][cupos]" class="form-input" min="1" value="30" required>
            </div>

            <div class="form-group">
                <label class="form-label">Modalidad</label>
                <select name="programas[${programaIndex}][modalidad]" class="form-select">
                    <option value="">-- Seleccione --</option>
                    <option value="Presencial">Presencial</option>
                    <option value="Virtual">Virtual</option>
                    <option value="Mixta">Mixta</option>
                </select>
            </div>

            <div class="form-group" style="display: flex; align-items: flex-end;">
                <button type="button" class="btn btn--sm btn--danger" onclick="removePrograma(this)">
                    🗑️ Eliminar
                </button>
            </div>
        </div>
    `;

    container.appendChild(programaRow);
    programaIndex++;
}

function removePrograma(button) {
    const programaRow = button.closest('.programa-row');
    programaRow.remove();

    const container = document.getElementById('programasContainer');
    if (container.children.length === 0) {
        container.innerHTML = '<p class="text-muted" id="noProgramasMessage">No hay programas agregados. Haga clic en "Agregar Programa".</p>';
    }
}

// Validación de fechas
document.addEventListener('DOMContentLoaded', function() {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    function validateDates() {
        if (fechaInicio.value && fechaFin.value) {
            if (fechaInicio.value > fechaFin.value) {
                fechaFin.setCustomValidity('La fecha de fin debe ser posterior a la fecha de inicio');
            } else {
                fechaFin.setCustomValidity('');
            }
        }
    }

    fechaInicio.addEventListener('change', validateDates);
    fechaFin.addEventListener('change', validateDates);
});
</script>

