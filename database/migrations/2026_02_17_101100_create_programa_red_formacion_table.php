<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programa_red_formacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->foreignId('red_formacion_id')->constrained('red_formacion')->cascadeOnDelete();
            $table->enum('estado', ['activo', 'inactivo', 'suspendido'])->default('activo');
            $table->timestamp('fecha_asignacion');
            $table->timestamp('fecha_desasignacion')->nullable();
            $table->foreignId('usuario_asigno_id')->nullable()->constrained('users');
            $table->foreignId('usuario_modifico_id')->nullable()->constrained('users');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['programa_id', 'red_formacion_id', 'estado'], 'prf_programa_red_estado_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programa_red_formacion');
    }
};
