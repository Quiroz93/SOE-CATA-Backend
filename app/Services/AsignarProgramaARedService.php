<?php

namespace App\Services;

use App\Models\ProgramaRedFormacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Events\ProgramaAsignadoARed;
use App\Events\ProgramaDesasignadoDeRed;

class AsignarProgramaARedService
{
    public static function asignar($programa_id, $red_formacion_id, $usuario_id, $observaciones = null)
    {
        // Validar duplicidad activa
        $existe = ProgramaRedFormacion::where('programa_id', $programa_id)
            ->where('red_formacion_id', $red_formacion_id)
            ->where('estado', 'activo')
            ->first();
        if ($existe) {
            throw new \Exception('Ya existe una relación activa entre este programa y red de formación.');
        }
        // Registrar asignación
        $relacion = ProgramaRedFormacion::create([
            'programa_id' => $programa_id,
            'red_formacion_id' => $red_formacion_id,
            'estado' => 'activo',
            'fecha_asignacion' => now(),
            'usuario_asigno_id' => $usuario_id,
            'observaciones' => $observaciones,
        ]);
        event(new ProgramaAsignadoARed($relacion));
        return $relacion;
    }
}

class DesasignarProgramaDeRedService
{
    public static function desasignar($relacion_id, $usuario_id, $observaciones = null)
    {
        $relacion = ProgramaRedFormacion::findOrFail($relacion_id);
        if ($relacion->estado !== 'activo') {
            throw new \Exception('La relación no está activa.');
        }
        $relacion->estado = 'inactivo';
        $relacion->fecha_desasignacion = now();
        $relacion->usuario_modifico_id = $usuario_id;
        $relacion->observaciones = $observaciones;
        $relacion->save();
        event(new ProgramaDesasignadoDeRed($relacion));
        return $relacion;
    }
}
