<div class="space-y-4">
    @if(isset($inscrito))
        <!-- Solo mostrar información, no editar FKs -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Preinscrito</label>
            <input 
                type="text" 
                value="{{ $inscrito->preinscrito->nombres }} {{ $inscrito->preinscrito->apellidos }}" 
                class="w-full px-3 py-2 border rounded-lg bg-gray-100" 
                disabled
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Programa</label>
            <input 
                type="text" 
                value="{{ $inscrito->programa->nombre }}" 
                class="w-full px-3 py-2 border rounded-lg bg-gray-100" 
                disabled
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Oferta</label>
            <input 
                type="text" 
                value="{{ $inscrito->oferta->nombre }}" 
                class="w-full px-3 py-2 border rounded-lg bg-gray-100" 
                disabled
            >
        </div>
    @else
        <!-- Crear nuevo: permitir selección -->
        <div>
            <label for="preinscrito_id" class="block text-sm font-medium text-gray-700 mb-2">
                Preinscrito <span class="text-red-500">*</span>
            </label>
            <select 
                name="preinscrito_id" 
                id="preinscrito_id" 
                class="w-full px-3 py-2 border rounded-lg @error('preinscrito_id') border-red-500 @enderror"
                required
            >
                <option value="">Seleccione un preinscrito</option>
                @foreach($preinscritos as $preinscrito)
                    <option value="{{ $preinscrito->id }}" {{ old('preinscrito_id') == $preinscrito->id ? 'selected' : '' }}>
                        {{ $preinscrito->nombres }} {{ $preinscrito->apellidos }} - {{ $preinscrito->documento }}
                    </option>
                @endforeach
            </select>
            @error('preinscrito_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="oferta_id" class="block text-sm font-medium text-gray-700 mb-2">
                Oferta <span class="text-red-500">*</span>
            </label>
            <select 
                name="oferta_id" 
                id="oferta_id" 
                class="w-full px-3 py-2 border rounded-lg @error('oferta_id') border-red-500 @enderror"
                required
            >
                <option value="">Seleccione una oferta</option>
                @foreach($ofertas as $oferta)
                    <option value="{{ $oferta->id }}" {{ old('oferta_id') == $oferta->id ? 'selected' : '' }}>
                        {{ $oferta->nombre }}
                    </option>
                @endforeach
            </select>
            @error('oferta_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="programa_id" class="block text-sm font-medium text-gray-700 mb-2">
                Programa <span class="text-red-500">*</span>
            </label>
            <select 
                name="programa_id" 
                id="programa_id" 
                class="w-full px-3 py-2 border rounded-lg @error('programa_id') border-red-500 @enderror"
                required
            >
                <option value="">Seleccione un programa</option>
                @foreach($programas as $programa)
                    <option value="{{ $programa->id }}" {{ old('programa_id') == $programa->id ? 'selected' : '' }}>
                        {{ $programa->nombre }}
                    </option>
                @endforeach
            </select>
            @error('programa_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <div>
        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
            Estado <span class="text-red-500">*</span>
        </label>
        <select 
            name="estado" 
            id="estado" 
            class="w-full px-3 py-2 border rounded-lg @error('estado') border-red-500 @enderror"
            required
        >
            <option value="inscrito" {{ (old('estado', $inscrito->estado ?? '') === 'inscrito') ? 'selected' : '' }}>Inscrito</option>
            <option value="matriculado" {{ (old('estado', $inscrito->estado ?? '') === 'matriculado') ? 'selected' : '' }}>Matriculado</option>
            <option value="retirado" {{ (old('estado', $inscrito->estado ?? '') === 'retirado') ? 'selected' : '' }}>Retirado</option>
        </select>
        @error('estado')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between pt-4">
        <a href="{{ route('admin.inscritos.index') }}" class="text-gray-600 hover:text-gray-900">
            Cancelar
        </a>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            {{ $buttonText ?? 'Guardar' }}
        </button>
    </div>
</div>
