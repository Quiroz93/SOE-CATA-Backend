@extends('admin.layouts.app')

@section('title', 'Ver Instructor')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">{{ $instructore->nombre }}</h1>
            <p class="admin-header__subtitle">Información del instructor</p>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.instructores.edit', $instructore) }}" class="btn btn--warning">
                ✏️ Editar
            </a>
            <a href="{{ route('admin.instructores.index') }}" class="btn btn--outline">
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
                    <dd>{{ $instructore->nombre }}</dd>
                </div>
                <div class="admin-details__item">
                    <dt>Estado:</dt>
                    <dd>
                        <span class="badge {{ $instructore->activo ? 'badge--success' : 'badge--danger' }}">
                            {{ $instructore->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </dd>
                </div>
                <div class="admin-details__item">
                    <dt>Fecha de registro:</dt>
                    <dd>{{ $instructore->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                @if($instructore->updated_at != $instructore->created_at)
                <div class="admin-details__item">
                    <dt>Última actualización:</dt>
                    <dd>{{ $instructore->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="admin-details__section">
            <h3 class="admin-details__section-title">Perfil Descriptivo</h3>
            <div class="admin-details__content">
                <p>{{ $instructore->perfil_descriptivo }}</p>
            </div>
        </div>

        @if($instructore->experiencia)
        <div class="admin-details__section">
            <h3 class="admin-details__section-title">Experiencia</h3>
            <div class="admin-details__content">
                <p style="white-space: pre-line;">{{ $instructore->experiencia }}</p>
            </div>
        </div>
        @endif

        <div class="admin-details__section">
            <h3 class="admin-details__section-title">Ofertas de Programas Asignadas</h3>
            @if($instructore->ofertaProgramas->count() > 0)
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Oferta</th>
                                <th>Programa</th>
                                <th>Centro</th>
                                <th>Cupos</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instructore->ofertaProgramas as $ofertaPrograma)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.ofertas.show', $ofertaPrograma->oferta) }}" class="link">
                                            {{ $ofertaPrograma->oferta->nombre }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.programas.show', $ofertaPrograma->programa) }}" class="link">
                                            {{ $ofertaPrograma->programa->nombre }}
                                        </a>
                                    </td>
                                    <td>{{ $ofertaPrograma->centro->nombre ?? 'N/A' }}</td>
                                    <td>{{ $ofertaPrograma->cupos }}</td>
                                    <td>
                                        <span class="badge {{ $ofertaPrograma->estado ? 'badge--success' : 'badge--danger' }}">
                                            {{ $ofertaPrograma->estado ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Este instructor no tiene ofertas de programas asignadas</p>
            @endif
        </div>
    </div>
</div>
@endsection
