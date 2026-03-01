@extends('admin.layouts.app')

@section('content')
@can('create', App\Models\User::class)
    <h1 class="text-3xl font-bold text-[#00304D] mb-8">Crear Usuario</h1>
    <form action="{{ route('admin.usuarios.store') }}" method="POST"
            class="bg-white p-8 rounded-lg shadow max-w-xl mx-auto">
        @csrf
        <div class="mb-4">
            <label for="name" class="block font-semibold text-[#00304D] mb-1">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="block w-full border-gray-300 rounded-lg focus:border-[#39A900] focus:ring-[#39A900]">
            @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block font-semibold text-[#00304D] mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                class="block w-full border-gray-300 rounded-lg focus:border-[#39A900] focus:ring-[#39A900]">
            @error('email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block font-semibold text-[#00304D] mb-1">Contraseña</label>
            <input type="password" name="password" id="password" required
                class="block w-full border-gray-300 rounded-lg focus:border-[#39A900] focus:ring-[#39A900]">
            @error('password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block font-semibold text-[#00304D] mb-1">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                class="block w-full border-gray-300 rounded-lg focus:border-[#39A900] focus:ring-[#39A900]">
        </div>
        <div class="flex justify-end mt-6">
            <button class="bg-[#39A900] text-white px-5 py-2 rounded-lg shadow hover:bg-[#007832] transition">
                Guardar
            </button>
        </div>
    </form>
@endcan
@endsection
