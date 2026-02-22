<?php
// Tabla: consolidaciones_preinscritos
// Propósito: Consolidar información de preinscritos para reportes y análisis.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consolidaciones_preinscritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preinscrito_id')->constrained('preinscritos')->cascadeOnDelete();
            $table->text('detalle')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consolidaciones_preinscritos');
    }
};
