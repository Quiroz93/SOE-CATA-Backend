<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('programa_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->foreignId('nivel_formacion_id')->constrained('niveles_formacion')->restrictOnDelete();
            $table->string('numero_ficha')->nullable();
            $table->text('requisitos')->nullable();
            $table->integer('duracion_meses')->nullable();
            $table->string('titulo_otorgado')->nullable();
            $table->string('codigo_snies')->nullable();
            $table->string('registro_calidad')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programa_detalles');
    }
};
