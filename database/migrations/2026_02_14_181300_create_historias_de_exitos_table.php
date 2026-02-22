<?php
// Tabla: historias_de_exitos
// Propósito: Almacena historias de éxito de egresados o participantes destacados.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historias_de_exitos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('imagen')->nullable();
            $table->string('autor')->nullable();
            $table->date('fecha_publicacion')->nullable();
            $table->boolean('publicada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historias_de_exitos');
    }
};
