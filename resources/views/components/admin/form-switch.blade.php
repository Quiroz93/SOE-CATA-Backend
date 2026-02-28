@props(['name','label','checked'=>false])

<div class="flex items-center gap-2 mb-4">
    <input 
        type="checkbox"
        name="{{ $name }}"
        value="1"
        {{ old($name, $checked) ? 'checked' : '' }}
        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
    >
    <label class="text-sm text-gray-700">{{ $label }}</label>
</div>
