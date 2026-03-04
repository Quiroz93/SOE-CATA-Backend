@extends('admin.layouts.app')

@section('title', 'Ver Competencia')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">{{ $competencia->nombre }}</h1>
            <p class="admin-header__subtitle">Información de la competencia</p>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.competencias.edit', $competencia) }}" class="btn btn--warning">
                ✏️ Editar
            </a>
            <a href="{{ route('admin.competencias.index') }}" class="btn btn--outline">
                ← Volver al listado
            </a>
        </div>
    </div>

    <div class="admin-details">
        <div class="admin-details__section">
            <h3 class="admin-details__section-title">Información General</h3>
            <dl class="admin-details__list">
                <div class="admin-details__item">
                    <dt>Nombre:</dt>
                    <dd>{{ $competencia->nombre }}</dd>
                </div>
                @if($competencia->area)
                <div class="admin-details__item">
                    <dt>Área:</dt>
                    <dd>{{ $competencia->area }}</dd>
                </div>
                @endif
                <div class="admin-details__item">
                    <dt>Estado:</dt>
                    <dd>
                        <span class="badge {{ $competencia->estado === 'publicado' ? 'badge--success' : 'badge--warning' }}">
                            {{ ucfirst($competencia->estado) }}
                        </span>
                    </dd>
                </div>
                <div class="admin-details__item">
                    <dt>Fecha de registro:</dt>
                    <dd>{{ $competencia->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                @if($competencia->updated_at != $competencia->created_at)
                <div class="admin-details__item">
                    <dt>Última actualización:</dt>
                    <dd>{{ $competencia->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
                @endif
            </dl>
        </div>

        @if($competencia->descripcion)
        <div class="admin-details__section">
            <h3 class="admin-details__section-title">Descripción</h3>
            <div class="admin-details__content">
                <p style="white-space: pre-line;">{{ $competencia->descripcion }}</p>
            </div>
        </div>
        @endif

        <div class="admin-details__section">
            <h3 class="admin-details__section-title">Programas Asociados</h3>
            @if($competencia->programas->count() > 0)
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Programa</th>
                                <th>Ficha</th>
                                <th>Nivel</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($competencia->programas as $programa)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.programas.show', $programa) }}" class="link">
                                            {{ $programa->nombre }}
                                        </a>
                                    </td>
                                    <td>{{ $programa->ficha }}</td>
                                    <td>{{ $programa->nivel }}</td>
                                    <td>
                                        <span class="badge {{ $programa->estado === 'publicado' ? 'badge--success' : 'badge--warning' }}">
                                            {{ ucfirst($programa->estado) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Esta competencia no está asociada a ningún programa</p>
            @endif
        </div>
    </div>
</div>
@endsection
