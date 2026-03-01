@csrf

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Nombre del Centro</label>
    <input type="text" name="nombre" value="{{ old('nombre', isset($centro) ? $centro->nombre : '') }}" required class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
    @error('nombre')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Código</label>
    <input type="text" name="codigo" value="{{ old('codigo', isset($centro) ? $centro->codigo : '') }}" required class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
    @error('codigo')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Dirección</label>
    <input type="text" name="direccion" value="{{ old('direccion', isset($centro) ? $centro->direccion : '') }}" class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
    @error('direccion')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
    <input type="text" name="telefono" value="{{ old('telefono', isset($centro) ? $centro->telefono : '') }}" class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
    @error('telefono')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Email</label>
    <input type="email" name="email" value="{{ old('email', isset($centro) ? $centro->email : '') }}" class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
    @error('email')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label class="flex items-center">
        <input type="hidden" name="estado" value="0">
        <input type="checkbox" name="estado" value="1" {{ old('estado', isset($centro) ? $centro->estado : true) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600">
        <span class="ml-2 text-sm text-gray-700">Centro activo</span>
    </label>
    @error('estado')
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>
