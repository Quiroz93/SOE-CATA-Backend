<?php

namespace App\Http\Controllers;

use App\Services\AsignarProgramaARedService;
use App\Services\DesasignarProgramaDeRedService;
use Illuminate\Http\Request;

class ProgramaRedFormacionController extends Controller
{
    public function asignar(Request $request)
    {
        $request->validate([
            'programa_id' => 'required|exists:programas,id',
            'red_formacion_id' => 'required|exists:red_formacion,id',
            'usuario_id' => 'required|exists:users,id',
            'observaciones' => 'nullable|string',
        ]);
        $relacion = AsignarProgramaARedService::asignar(
            $request->programa_id,
            $request->red_formacion_id,
            $request->usuario_id,
            $request->observaciones
        );
        return response()->json($relacion);
    }

    public function desasignar(Request $request)
    {
        $request->validate([
            'relacion_id' => 'required|exists:programa_red_formacion,id',
            'usuario_id' => 'required|exists:users,id',
            'observaciones' => 'nullable|string',
        ]);
        $relacion = DesasignarProgramaDeRedService::desasignar(
            $request->relacion_id,
            $request->usuario_id,
            $request->observaciones
        );
        return response()->json($relacion);
    }
}
