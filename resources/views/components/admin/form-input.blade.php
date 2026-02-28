@props(['name','label','type'=>'text','value'=>null])

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">
        {{ $label }}
    </label>

    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-green-200"
    >

    @error($name)
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>
