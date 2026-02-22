<?php
// Tabla: novedades_preinscritos
// Propósito: Almacena novedades asociadas a preinscritos y tipos de novedad.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('novedades_preinscritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preinscrito_id')->constrained('preinscritos')->cascadeOnDelete();
            $table->foreignId('tipo_novedad_id')->constrained('tipos_novedad')->cascadeOnDelete();
            $table->text('detalle')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novedades_preinscritos');
    }
};
