<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('oferta_programa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oferta_id')->constrained('ofertas')->cascadeOnDelete();
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('instructores')->cascadeOnDelete();
            $table->integer('cupos');
            $table->string('modalidad')->nullable();
            $table->boolean('estado')->default(true); // 1=activo, 0=inactivo
            $table->integer('version')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['oferta_id', 'programa_id', 'version']);
        });
    }

    public function down(): void
    {
        // Eliminar primero la tabla oferta_programa para evitar errores de clave foránea
        Schema::dropIfExists('oferta_programa');
    }
};
