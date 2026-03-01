@extends('admin.layouts.app')

@section('title', 'Crear Preinscrito')

@section('content')
<div class="admin-page-container">
    <div class="admin-header">
        <h1 class="admin-header__title">Crear Nuevo Preinscrito</h1>
    </div>
    
    <div class="admin-form-card">
        <form action="{{ route('admin.preinscritos.store') }}" method="POST" class="admin-form">
            @csrf
            
            @if($errors->any())
                <div class="alert alert--danger">
                    <ul class="alert__list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @include('admin.preinscritos._form')
            
            <div class="form-actions">
                <a href="{{ route('admin.preinscritos.index') }}" class="btn btn--secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn--primary">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
