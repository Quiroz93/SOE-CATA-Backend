<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Oferta;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener estadísticas
        $data = [
            'totalUsuarios' => $this->getTotalUsuarios(),
            'totalOfertas' => $this->getTotalOfertas(),
            'ofertasActivas' => $this->getOfertasActivas(),
            'ofertasVencidas' => $this->getOfertasVencidas(),
            'actividadReciente' => $this->getActividadReciente(),
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Get total count of users
     * 
     * @return int
     */
    private function getTotalUsuarios(): int
    {
        return User::count();
    }

    /**
     * Get total count of offers
     * 
     * @return int
     */
    private function getTotalOfertas(): int
    {
        return Oferta::count();
    }

    /**
     * Get count of active offers
     * Activas = estado = true Y fecha_fin >= hoy
     * 
     * @return int
     */
    private function getOfertasActivas(): int
    {
        return Oferta::where('estado', true)
            ->where('fecha_fin', '>=', Carbon::today())
            ->count();
    }

    /**
     * Get count of expired offers
     * Vencidas = estado = true Y fecha_fin < hoy
     * 
     * @return int
     */
    private function getOfertasVencidas(): int
    {
        return Oferta::where('estado', true)
            ->where('fecha_fin', '<', Carbon::today())
            ->count();
    }

    /**
     * Get recent activity
     * Retorna actividades recientes del sistema
     * 
     * @return array
     */
    private function getActividadReciente(): array
    {
        $actividades = [];

        // Últimas ofertas públicadas
        $ultimasOfertas = Oferta::orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        foreach ($ultimasOfertas as $oferta) {
            $actividades[] = "Oferta \"{$oferta->nombre}\" publicada";
        }

        // Últimos usuarios registrados
        $ultimosUsuarios = User::orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        foreach ($ultimosUsuarios as $usuario) {
            $actividades[] = "Nuevo usuario \"{$usuario->name}\" registrado";
        }

        // Si no hay actividades, agregar actividades por defecto
        if (empty($actividades)) {
            $actividades = [
                'Nuevo usuario registrado',
                'Oferta "Análisis de Software" publicada',
                'Oferta "Turismo Rural" actualizada',
                'Usuario administrador editó configuración',
            ];
        }

        return $actividades;
    }
}
