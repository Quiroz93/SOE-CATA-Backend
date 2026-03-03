<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('programas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('ficha')->unique();
            $table->string('nivel');
            $table->text('descripcion')->nullable();
            $table->string('modalidad')->nullable();
            $table->string('municipio')->nullable();
            $table->string('duracion')->nullable();
            $table->string('imagen_portada')->nullable();
            $table->foreignId('nivel_formacion_id')->nullable()->constrained('niveles_formacion')->nullOnDelete();
            $table->enum('estado', ['borrador', 'publicado', 'archivado', 'cancelado'])->default('borrador');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programas');
    }
};
