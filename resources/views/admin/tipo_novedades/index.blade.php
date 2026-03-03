@extends('admin.layouts.app')

@section('title', 'Gestión de Tipos de Novedades')

@section('content')
<div class="admin-page">
    <div class="admin-header">
        <h1 class="admin-header__title">Gestión de Tipos de Novedades</h1>
        <div class="admin-header__actions">
            <a href="{{ route('admin.tipo-novedad.create') }}" class="btn btn--primary">
                + Nuevo Tipo
            </a>
            <a href="{{ route('admin.novedades.index') }}" class="btn btn--secondary">
                ← Volver a Novedades
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert--success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->has('error'))
        <div class="alert alert--danger">
            {{ $errors->first('error') }}
        </div>
    @endif

    <!-- Sección de Filtros -->
    <div class="filter-card">
        <div class="filter-card__header">
            <h3 class="filter-card__title">🔍 Filtros de Búsqueda</h3>
            <button type="button" class="filter-toggle" onclick="toggleFilterForm()">
                <span class="filter-toggle__text">-</span>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.tipo-novedad.index') }}" class="filter-form" id="filterForm">
            <div class="filter-form__grid">
                <div class="filter-form__group">
                    <label for="nombre" class="filter-form__label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="filter-form__input" 
                           placeholder="Buscar por nombre..." value="{{ request('nombre') }}">
                </div>

                <div class="filter-form__group">
                    <label for="descripcion" class="filter-form__label">Descripción</label>
                    <input type="text" name="descripcion" id="descripcion" class="filter-form__input" 
                           placeholder="Buscar por descripción..." value="{{ request('descripcion') }}">
                </div>
            </div>

            <div class="filter-form__actions">
                <button type="submit" class="btn btn--secondary">🔍 Buscar</button>
                <a href="{{ route('admin.tipo-novedad.index') }}" class="btn btn--secondary">🔄 Limpiar</a>
            </div>
        </form>
    </div>

    <!-- Tabla de Tipos de Novedades -->
    <div class="admin-table-card">
        @if($tiposNovedad->count() > 0)
            <table class="admin-table">
                <thead class="admin-table__head">
                    <tr class="admin-table__row">
                        <th class="admin-table__cell admin-table__cell--header">Nombre</th>
                        <th class="admin-table__cell admin-table__cell--header">Descripción</th>
                        <th class="admin-table__cell admin-table__cell--header">Creado</th>
                        <th class="admin-table__cell admin-table__cell--header">Acciones</th>
                    </tr>
                </thead>
                <tbody class="admin-table__body">
                    @foreach($tiposNovedad as $tipo)
                        <tr class="admin-table__row">
                            <td class="admin-table__cell">
                                <strong>{{ $tipo->nombre }}</strong>
                            </td>
                            <td class="admin-table__cell">
                                {{ $tipo->descripcion ?? 'Sin descripción' }}
                            </td>
                            <td class="admin-table__cell">
                                {{ $tipo->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="admin-table__cell">
                                <div class="admin-table__actions">
                                    <a href="{{ route('admin.tipo-novedad.edit', $tipo) }}" 
                                       class="btn btn--sm btn--info" title="Editar">
                                        ✏️ Editar
                                    </a>
                                    <form action="{{ route('admin.tipo-novedad.destroy', $tipo) }}" 
                                          method="POST" 
                                          style="display:inline;" 
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este tipo de novedad?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--sm btn--danger">
                                            🗑️ Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación -->
            @if($tiposNovedad->hasPages())
                <div class="admin-pagination">
                    {{ $tiposNovedad->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="admin-table__empty">
                <p>No hay tipos de novedades registrados.</p>
                <a href="{{ route('admin.tipo-novedad.create') }}" class="btn btn--primary">
                    + Crear el primer tipo
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function toggleFilterForm() {
    const filterForm = document.getElementById('filterForm');
    const filterToggle = document.querySelector('.filter-toggle__text');
    
    if (filterForm.style.display === 'none') {
        filterForm.style.display = 'grid';
        filterToggle.textContent = '-';
    } else {
        filterForm.style.display = 'none';
        filterToggle.textContent = '+';
    }
}
</script>
@endsection
