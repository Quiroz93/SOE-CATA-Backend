@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('styles')
    @vite(['resources/css/app.css'])
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Hidden data container for JavaScript -->
        <div id="dashboardData" 
             data-meses='{{ json_encode($meses) }}'
             data-ofertas-por-mes='{{ json_encode($ofertasPorMes) }}'
             data-ofertas-activas='{{ $ofertasActivas }}'
             data-ofertas-vencidas='{{ $ofertasVencidas }}'
             style="display: none;">
        </div>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard General</h1>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Total Usuarios</h3>
                <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalUsuarios }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Total Ofertas</h3>
                <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalOfertas }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Ofertas Activas</h3>
                <div class="mt-2 text-3xl font-bold text-green-600">{{ $ofertasActivas }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Ofertas Vencidas</h3>
                <div class="mt-2 text-3xl font-bold text-red-600">{{ $ofertasVencidas }}</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ofertas por Mes</h3>
                <canvas id="ofertasMes"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado de Ofertas</h3>
                <canvas id="estadoOfertas"></canvas>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actividad Reciente</h3>
            <ul class="space-y-2">
                @foreach($actividades as $actividad)
                    <li class="text-gray-700 py-2 border-b border-gray-200">{{ $actividad }}</li>
                @endforeach
            </ul>
        </div>

    </div>
@endsection

@section('scripts')
    @vite(['resources/js/admin/dashboard.js'])
@endsection