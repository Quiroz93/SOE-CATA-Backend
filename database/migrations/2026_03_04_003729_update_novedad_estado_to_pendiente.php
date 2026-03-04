<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar registros con estado 'Novedad' a 'Pendiente'
        // El estado 'Novedad' ha sido eliminado y ahora se usa un checkbox para indicar novedades
        DB::table('preinscritos')
            ->where('estado', 'Novedad')
            ->update(['estado' => 'Pendiente']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No se revierte esta migración ya que el estado 'Novedad' ya no existe
        // Los registros quedan como 'Pendiente'
    }
};
