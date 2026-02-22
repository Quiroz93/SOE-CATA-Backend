{{-- Formulario reutilizable para crear/editar programa --}}
<form method="POST" action="{{ isset($mode) && $mode === 'edit' ? route('admin.programas.update', $programa ?? null) : route('admin.programas.store') }}" class="space-y-6">
    @csrf
    @if(isset($mode) && $mode === 'edit')
        @method('PUT')
    @endif
    {{-- Campo: Nombre del programa --}}
    <div>
        <label for="nombre" class="block text-sm font-medium">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-input mt-1 block w-full" required>
    </div>
    {{-- Campo: Descripción --}}
    <div>
        <label for="descripcion" class="block text-sm font-medium">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-textarea mt-1 block w-full"></textarea>
    </div>
    {{-- Campo: Nivel de formación --}}
    <div>
        <label for="nivel" class="block text-sm font-medium">Nivel de Formación</label>
        <select name="nivel" id="nivel" class="form-select mt-1 block w-full">
            {{-- Opciones dinámicas --}}
        </select>
    </div>
    {{-- Campo: Redes de Formación --}}
    <div>
        <label for="redes_ids" class="block text-sm font-medium">Redes de Formación</label>
        <select name="redes_ids[]" id="redes_ids" class="form-select mt-1 block w-full" multiple required>
            @foreach($redesFormacion as $red)
                <option value="{{ $red->id }}" {{ (isset($programa) && $programa->redesFormacion->contains($red->id)) ? 'selected' : '' }}>{{ $red->nombre }}</option>
            @endforeach
        </select>
        <small class="text-gray-500">Mantén presionada la tecla Ctrl (Windows) o Cmd (Mac) para seleccionar varias opciones.</small>
    </div>
    {{-- Botón de envío --}}
    <div>
        <button type="submit" class="btn btn-primary">{{ $mode === 'edit' ? 'Actualizar' : 'Crear' }}</button>
    </div>
    {{-- Punto de montaje Vue para validaciones dinámicas --}}
    <div id="vue-programas-form"></div>
</form>
