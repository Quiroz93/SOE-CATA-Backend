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
            $table->string('estado'); // Enum recomendado, pero string para flexibilidad
            $table->integer('version')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['oferta_id', 'programa_id', 'version']);
            $table->index(['oferta_id', 'programa_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oferta_programa');
    }
};
