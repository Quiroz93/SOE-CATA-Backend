<?php
// Tabla: inscritos
// Propósito: Almacena los inscritos finales, vinculados a preinscritos, ofertas y programas.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preinscrito_id')->constrained('preinscritos')->cascadeOnDelete();
            $table->foreignId('oferta_id')->constrained('ofertas')->cascadeOnDelete();
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->string('estado')->default('inscrito');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscritos');
    }
};
