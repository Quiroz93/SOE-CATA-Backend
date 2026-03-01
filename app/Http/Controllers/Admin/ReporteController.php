<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Usuarios por rol (Spatie Permission)
        $rolesTable = config('permission.table_names.roles', 'roles');
        $modelHasRolesTable = config('permission.table_names.model_has_roles', 'model_has_roles');

        $usuariosPorRol = DB::table('users as u')
            ->leftJoin("{$modelHasRolesTable} as mhr", function ($join) {
                $join->on('mhr.model_id', '=', 'u.id')
                    ->where('mhr.model_type', '=', User::class);
            })
            ->leftJoin("{$rolesTable} as r", 'r.id', '=', 'mhr.role_id')
            ->selectRaw("COALESCE(r.name, 'Sin rol') as role, COUNT(DISTINCT u.id) as total")
            ->groupByRaw("COALESCE(r.name, 'Sin rol')")
            ->pluck('total', 'role')
            ->toArray();

        // Centro con más ofertas
        $centrosMasOfertas = DB::table('centros as c')
            ->leftJoin('ofertas as o', 'o.centro_id', '=', 'c.id')
            ->selectRaw('c.nombre, COUNT(o.id) as ofertas_count')
            ->groupBy('c.id', 'c.nombre')
            ->orderByDesc('ofertas_count')
            ->limit(5)
            ->get();

        // Programas más demandados
        $programasMasDemandados = DB::table('programas as p')
            ->leftJoin('oferta_programa as op', 'op.programa_id', '=', 'p.id')
            ->selectRaw('p.nombre, COUNT(op.id) as ofertas_count')
            ->groupBy('p.id', 'p.nombre')
            ->orderByDesc('ofertas_count')
            ->limit(5)
            ->get();

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
