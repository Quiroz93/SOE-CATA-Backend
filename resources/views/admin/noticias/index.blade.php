@extends('admin.layouts.app')

@section('title', 'Noticias')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="admin-header__content">
            <h1 class="admin-header__title">Noticias</h1>
            <p class="admin-header__subtitle">Gestión de contenido editorial</p>
        </div>
        <div class="admin-header__actions">
            <a href="{{ route('admin.noticias.create') }}" class="btn btn--primary">
                <span class="icon">+</span> Nueva Noticia
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="admin-filters">
        <form method="GET" action="{{ route('admin.noticias.index') }}" class="filters-form">
            <div class="filters-form__group">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Buscar noticias..." class="form-input">
            </div>
            <div class="filters-form__group">
                <select name="publicada" class="form-select">
                    <option value="">-- Todos los Estados --</option>
                    <option value="1" {{ request('publicada') === '1' ? 'selected' : '' }}>Publicadas</option>
                    <option value="0" {{ request('publicada') === '0' ? 'selected' : '' }}>Borradores</option>
                </select>
            </div>
            <div class="filters-form__actions">
                <button type="submit" class="btn btn--secondary">Filtrar</button>
                <a href="{{ route('admin.noticias.index') }}" class="btn btn--outline">Limpiar</a>
            </div>
        </form>
    </div>

    @if(session('success'))<div class="alert alert--success">{{ session('success') }}</div>@endif

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr><th>Título</th><th>Fecha Publicación</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($noticias as $noticia)
                    <tr>
                        <td><strong>{{ $noticia->titulo }}</strong><br><small class="text-muted">{{ Str::limit($noticia->contenido, 80) }}</small></td>
                        <td>{{ $noticia->fecha_publicacion ? $noticia->fecha_publicacion->format('d/m/Y') : 'Sin fecha' }}</td>
                        <td><span class="badge {{ $noticia->publicada ? 'badge--success' : 'badge--warning' }}">{{ $noticia->publicada ? 'Publicada' : 'Borrador' }}</span></td>
                        <td>
                            <div class="admin-table__actions">
                                <a href="{{ route('admin.noticias.show', $noticia) }}" class="btn btn--sm btn--info" title="Ver"><span class="icon">👁</span></a>
                                <a href="{{ route('admin.noticias.edit', $noticia) }}" class="btn btn--sm btn--warning" title="Editar"><span class="icon">✏️</span></a>
                                <form action="{{ route('admin.noticias.destroy', $noticia) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Eliminar noticia?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn--sm btn--danger" title="Eliminar"><span class="icon">🗑️</span></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No se encontraron noticias</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($noticias->hasPages())<div class="admin-pagination">{{ $noticias->links('admin.pagination.default') }}</div>@endif
</div>
@endsection