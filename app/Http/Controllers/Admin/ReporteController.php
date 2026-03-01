<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\User;
use App\Models\Centro;
use App\Models\Programa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    /**
     * Display reports dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return $this->getReports($request);
    }

    /**
     * Generate reports with filters
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    private function getReports(Request $request)
    {
        // Date range filters
        $startDate = $request->get('start_date') ? Carbon::createFromFormat('Y-m-d', $request->get('start_date')) : Carbon::now()->subMonths(3);
        $endDate = $request->get('end_date') ? Carbon::createFromFormat('Y-m-d', $request->get('end_date')) : Carbon::now();

        // KPI Metrics
        $totalOfertas = Oferta::count();
        $ofertasActivas = Oferta::where('estado', 'activa')->count();
        $ofertasVencidas = Oferta::where('estado', 'vencida')->count();
        $totalUsuarios = User::count();

        // Ofertas por estado
        $ofertasPorEstado = [
            'activa' => Oferta::where('estado', 'activa')->count(),
            'vencida' => Oferta::where('estado', 'vencida')->count(),
            'inactiva' => Oferta::where('estado', 'inactiva')->count(),
        ];

        // Usuarios por rol
        $usuariosPorRol = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        // Centro con más ofertas
        $centrosMasOfertas = Centro::withCount('ofertas')
            ->orderBy('ofertas_count', 'desc')
            ->limit(5)
            ->get(['nombre', 'ofertas_count']);

        // Programas más demandados
        $programasMasDemandados = Programa::withCount('ofertas')
            ->orderBy('ofertas_count', 'desc')
            ->limit(5)
            ->get(['nombre', 'ofertas_count']);

        // Ofertas de últimos 30 días
        $ofertasRecientes = Oferta::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'nombre', 'estado', 'centro_id', 'created_at']);

        // Chart data: Ofertas por mes
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $ofertasPorMes = Oferta::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        $dataMes = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataMes[] = $ofertasPorMes[$i] ?? 0;
        }

        return view('admin.reportes.index', [
            'totalOfertas' => $totalOfertas,
            'ofertasActivas' => $ofertasActivas,
            'ofertasVencidas' => $ofertasVencidas,
            'totalUsuarios' => $totalUsuarios,
            'ofertasPorEstado' => $ofertasPorEstado,
            'usuariosPorRol' => $usuariosPorRol,
            'centrosMasOfertas' => $centrosMasOfertas,
            'programasMasDemandados' => $programasMasDemandados,
            'ofertasRecientes' => $ofertasRecientes,
            'meses' => $meses,
            'ofertasPorMes' => $dataMes,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }
}
