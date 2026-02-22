<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preinscritos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('oferta_id');
            $table->unsignedBigInteger('oferta_programa_id');
            $table->string('nombre');
            $table->string('documento');
            $table->string('correo');
            $table->string('estado')->default('pendiente');
            $table->timestamps();
            $table->foreign('oferta_id')->references('id')->on('ofertas')->onDelete('cascade');
            $table->foreign('oferta_programa_id')->references('id')->on('oferta_programa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preinscritos');
    }
};
