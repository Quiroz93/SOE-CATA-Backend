<?php
// Tabla: backups
// Propósito: Almacena registros de backups realizados sobre ofertas.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oferta_id')->constrained('ofertas')->cascadeOnDelete();
            $table->string('tipo');
            $table->string('archivo');
            $table->foreignId('usuario_admin_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('respaldada_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
