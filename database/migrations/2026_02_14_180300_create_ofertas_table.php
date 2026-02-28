<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centro_id')->constrained('centros')->restrictOnDelete();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('estado')->default('activo'); // Enum recomendado, pero string para flexibilidad 
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('oferta_programa');
        Schema::dropIfExists('ofertas');
    }
};
