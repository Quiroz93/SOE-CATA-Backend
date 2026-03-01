@csrf

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Oferta Programa</label>
    <select name="oferta_programa_id" required class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
        <option value="">-- Seleccionar --</option>
        @foreach($ofertasPrograma as $op)
            <option value="{{ $op->id }}" {{ old('oferta_programa_id', isset($preinscrito) ? $preinscrito->oferta_programa_id : '') == $op->id ? 'selected' : '' }}>
                {{ $op->oferta->nombre ?? 'N/A' }} - {{ $op->programa->nombre ?? 'N/A' }}
            </option>
        @endforeach
    </select>
    @error('oferta_programa_id')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Oferta ID</label>
    <input type="number" name="oferta_id" value="{{ old('oferta_id', isset($preinscrito) ? $preinscrito->oferta_id : '') }}" required class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
    @error('oferta_id')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre', isset($preinscrito) ? $preinscrito->nombre : '') }}" required class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
    @error('nombre')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Documento</label>
    <input type="text" name="documento" value="{{ old('documento', isset($preinscrito) ? $preinscrito->documento : '') }}" required class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
    @error('documento')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Correo</label>
    <input type="email" name="correo" value="{{ old('correo', isset($preinscrito) ? $preinscrito->correo : '') }}" required class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
    @error('correo')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Estado</label>
    <select name="estado" required class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
        <option value="pendiente" {{ old('estado', isset($preinscrito) ? $preinscrito->estado : 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        <option value="aceptado" {{ old('estado', isset($preinscrito) ? $preinscrito->estado : '') == 'aceptado' ? 'selected' : '' }}>Aceptado</option>
        <option value="rechazado" {{ old('estado', isset($preinscrito) ? $preinscrito->estado : '') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
    </select>
    @error('estado')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>
