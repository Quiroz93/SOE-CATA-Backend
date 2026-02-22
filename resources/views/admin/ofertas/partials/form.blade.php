{{-- Formulario reutilizable para crear/editar oferta --}}
<form method="POST" action="{{ isset($mode) && $mode === 'edit' ? route('admin.ofertas.update', $oferta ?? null) : route('admin.ofertas.store') }}" class="space-y-6">
    @csrf
    @if(isset($mode) && $mode === 'edit')
        @method('PUT')
    @endif
    {{-- Campo: Nombre de la oferta --}}
    <div>
        <label for="nombre" class="block text-sm font-medium">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-input mt-1 block w-full" required>
    </div>
    {{-- Campo: Descripción --}}
    <div>
        <label for="descripcion" class="block text-sm font-medium">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-textarea mt-1 block w-full"></textarea>
    </div>
    {{-- Campo: Estado de la oferta --}}
    <div>
        <label for="estado" class="block text-sm font-medium">Estado</label>
        <select name="estado" id="estado" class="form-select mt-1 block w-full">
            {{-- Opciones dinámicas --}}
        </select>
    </div>
    {{-- Botón de envío --}}
    <div>
        <button type="submit" class="btn btn-primary">{{ $mode === 'edit' ? 'Actualizar' : 'Crear' }}</button>
    </div>
    {{-- Punto de montaje Vue para validaciones dinámicas --}}
    <div id="vue-ofertas-form"></div>
</form>
