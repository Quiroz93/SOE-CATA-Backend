<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\Preinscrito;
use App\Models\Programa;
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
        $startDate = $request->input('start_date') ? Carbon::createFromFormat('Y-m-d', $request->input('start_date')) : Carbon::now()->subMonths(3);
        $endDate = $request->input('end_date') ? Carbon::createFromFormat('Y-m-d', $request->input('end_date')) : Carbon::now();

        // Additional filters
        $programaFilter = $request->input('programa_id');
        $estadoFilter = $request->input('estado');

        // Get all programs for filter dropdown
        $programas = Programa::orderBy('nombre')->get(['id', 'nombre']);

        // KPI Metrics
        $totalOfertas = Oferta::count();
        $ofertasActivas = Oferta::where('estado', 'activa')->count();
        $ofertasVencidas = Oferta::where('estado', 'vencida')->count();
        $totalUsuarios = User::count();

        // Preinscritos KPIs
        $basePreinscritosQuery = Preinscrito::query();
        
        if ($programaFilter) {
            $basePreinscritosQuery->whereHas('ofertaPrograma', function($q) use ($programaFilter) {
                $q->where('programa_id', $programaFilter);
            });
        }
        
        if ($estadoFilter) {
            $basePreinscritosQuery->where('estado', $estadoFilter);
        }
        
        $totalPreinscritos = (clone $basePreinscritosQuery)->count();
        $preinscritosPendientes = (clone $basePreinscritosQuery)->where('estado', 'pendiente')->count();
        $preinscritosAceptados = (clone $basePreinscritosQuery)->where('estado', 'aceptado')->count();
        $preinscritosRechazados = (clone $basePreinscritosQuery)->where('estado', 'rechazado')->count();

        // Ofertas por estado
        $ofertasPorEstado = [
            'activa' => Oferta::where('estado', 'activa')->count(),
            'vencida' => Oferta::where('estado', 'vencida')->count(),
            'inactiva' => Oferta::where('estado', 'inactiva')->count(),
        ];

        // Preinscritos por estado
        $preinscritosPorEstado = [
            'pendiente' => $preinscritosPendientes,
            'aceptado' => $preinscritosAceptados,
            'rechazado' => $preinscritosRechazados,
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

        // Chart data: Preinscritos por mes
        $preinscritosPorMesQuery = Preinscrito::selectRaw('MONTH(created_at) as mes, COUNT(*) as total');
        
        if ($programaFilter) {
            $preinscritosPorMesQuery->whereHas('ofertaPrograma', function($q) use ($programaFilter) {
                $q->where('programa_id', $programaFilter);
            });
        }
        
        if ($estadoFilter) {
            $preinscritosPorMesQuery->where('estado', $estadoFilter);
        }
        
        $preinscritosPorMes = $preinscritosPorMesQuery->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        $dataPreinscritosMes = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataPreinscritosMes[] = $preinscritosPorMes[$i] ?? 0;
        }

        // Programas con más preinscritos
        $programasMasPreinscritosQuery = DB::table('programas as p')
            ->join('oferta_programa as op', 'op.programa_id', '=', 'p.id')
            ->join('preinscritos as pr', 'pr.oferta_programa_id', '=', 'op.id');
        
        if ($programaFilter) {
            $programasMasPreinscritosQuery->where('p.id', $programaFilter);
        }
        
        if ($estadoFilter) {
            $programasMasPreinscritosQuery->where('pr.estado', $estadoFilter);
        }
        
        $programasMasPreinscritos = $programasMasPreinscritosQuery
            ->selectRaw('p.nombre, COUNT(pr.id) as preinscritos_count')
            ->groupBy('p.id', 'p.nombre')
            ->orderByDesc('preinscritos_count')
            ->limit(5)
            ->get();

        // Preparar datos para gráfica de barras
        $programasNombres = $programasMasPreinscritos->pluck('nombre')->toArray();
        $programasPreinscritos = $programasMasPreinscritos->pluck('preinscritos_count')->toArray();

        // Programa líder (más preinscritos)
        $programaLider = $programasMasPreinscritos->first();
        $programaLiderNombre = $programaLider ? $programaLider->nombre : 'N/A';
        $programaLiderCount = $programaLider ? $programaLider->preinscritos_count : 0;

        // Preinscritos por trimestre (últimos 8 trimestres)
        $trimestres = [];
        $dataPreinscritosTrimestre = [];
        
        for ($i = 7; $i >= 0; $i--) {
            $fecha = now()->subQuarters($i);
            $year = $fecha->year;
            $quarter = $fecha->quarter;
            
            $trimestres[] = "Q{$quarter} {$year}";
            
            $trimestreQuery = Preinscrito::whereYear('created_at', $year)
                ->whereRaw('QUARTER(created_at) = ?', [$quarter]);
            
            if ($programaFilter) {
                $trimestreQuery->whereHas('ofertaPrograma', function($q) use ($programaFilter) {
                    $q->where('programa_id', $programaFilter);
                });
            }
            
            if ($estadoFilter) {
                $trimestreQuery->where('estado', $estadoFilter);
            }
            
            $count = $trimestreQuery->count();
            $dataPreinscritosTrimestre[] = $count;
        }

        // Variación porcentual entre trimestre actual y anterior
        $trimestreActual = now();
        $trimestreAnterior = now()->subQuarter();
        
        $preinscritosTrimestreActualQuery = Preinscrito::whereYear('created_at', $trimestreActual->year)
            ->whereRaw('QUARTER(created_at) = ?', [$trimestreActual->quarter]);
        
        $preinscritosTrimestreAnteriorQuery = Preinscrito::whereYear('created_at', $trimestreAnterior->year)
            ->whereRaw('QUARTER(created_at) = ?', [$trimestreAnterior->quarter]);
        
        if ($programaFilter) {
            $preinscritosTrimestreActualQuery->whereHas('ofertaPrograma', function($q) use ($programaFilter) {
                $q->where('programa_id', $programaFilter);
            });
            $preinscritosTrimestreAnteriorQuery->whereHas('ofertaPrograma', function($q) use ($programaFilter) {
                $q->where('programa_id', $programaFilter);
            });
        }
        
        if ($estadoFilter) {
            $preinscritosTrimestreActualQuery->where('estado', $estadoFilter);
            $preinscritosTrimestreAnteriorQuery->where('estado', $estadoFilter);
        }
        
        $preinscritosTrimestreActual = $preinscritosTrimestreActualQuery->count();
        $preinscritosTrimestreAnterior = $preinscritosTrimestreAnteriorQuery->count();
        
        $variacionPorcentual = 0;
        $tendencia = 'neutral';
        
        if ($preinscritosTrimestreAnterior > 0) {
            $variacionPorcentual = (($preinscritosTrimestreActual - $preinscritosTrimestreAnterior) / $preinscritosTrimestreAnterior) * 100;
            $tendencia = $variacionPorcentual > 0 ? 'up' : ($variacionPorcentual < 0 ? 'down' : 'neutral');
        } elseif ($preinscritosTrimestreActual > 0) {
            $variacionPorcentual = 100;
            $tendencia = 'up';
        }

        // Preinscritos por año (últimos 5 años)
        $años = [];
        $dataPreinscritosAño = [];
        for ($i = 4; $i >= 0; $i--) {
            $año = now()->subYears($i)->year;
            $años[] = $año;
            $count = Preinscrito::whereYear('created_at', $año)->count();
            $dataPreinscritosAño[] = $count;
        }

        // Ofertas con más preinscritos
        $ofertasMasPreinscritos = DB::table('ofertas as o')
            ->join('oferta_programa as op', 'op.oferta_id', '=', 'o.id')
            ->join('preinscritos as pr', 'pr.oferta_programa_id', '=', 'op.id')
            ->selectRaw('o.nombre, COUNT(pr.id) as preinscritos_count')
            ->groupBy('o.id', 'o.nombre')
            ->orderByDesc('preinscritos_count')
            ->limit(5)
            ->get();

        // Detalle de preinscritos recientes
        $preinscritosDetalleQuery = Preinscrito::with(['ofertaPrograma.oferta', 'ofertaPrograma.programa']);
        
        if ($programaFilter) {
            $preinscritosDetalleQuery->whereHas('ofertaPrograma', function($q) use ($programaFilter) {
                $q->where('programa_id', $programaFilter);
            });
        }
        
        if ($estadoFilter) {
            $preinscritosDetalleQuery->where('estado', $estadoFilter);
        }
        
        $preinscritosDetalle = $preinscritosDetalleQuery->orderByDesc('created_at')
            ->paginate(10);

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
            // Preinscritos data
            'totalPreinscritos' => $totalPreinscritos,
            'preinscritosPendientes' => $preinscritosPendientes,
            'preinscritosAceptados' => $preinscritosAceptados,
            'preinscritosRechazados' => $preinscritosRechazados,
            'preinscritosPorEstado' => $preinscritosPorEstado,
            'preinscritosPorMes' => $dataPreinscritosMes,
            'programasMasPreinscritos' => $programasMasPreinscritos,
            // New preinscritos data
            'años' => $años,
            'preinscritosAño' => $dataPreinscritosAño,
            'ofertasMasPreinscritos' => $ofertasMasPreinscritos,
            'preinscritosDetalle' => $preinscritosDetalle,
            // Comparativa por programa
            'programasNombres' => $programasNombres,
            'programasPreinscritos' => $programasPreinscritos,
            // Evolución por trimestre
            'trimestres' => $trimestres,
            'preinscritosTrimestre' => $dataPreinscritosTrimestre,
            // Variación porcentual
            'variacionPorcentual' => $variacionPorcentual,
            'tendencia' => $tendencia,
            'preinscritosTrimestreActual' => $preinscritosTrimestreActual,
            'preinscritosTrimestreAnterior' => $preinscritosTrimestreAnterior,
            // Filtros
            'programas' => $programas,
            'programaFilter' => $programaFilter,
            'estadoFilter' => $estadoFilter,
            // Programa líder
            'programaLiderNombre' => $programaLiderNombre,
            'programaLiderCount' => $programaLiderCount,
        ]);
    }
}
