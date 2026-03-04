<?php

use App\Domain\Programa\Enums\EstadoPreinscrito;
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
            $table->unsignedBigInteger('programa_id')->nullable();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('tipo_documento', 10);
            $table->string('documento');
            $table->string('correo');
            $table->enum('estado', EstadoPreinscrito::values())
                ->default(EstadoPreinscrito::tryFromInput('pendiente')?->value ?? EstadoPreinscrito::cases()[0]->value);
            $table->timestamps();
            $table->foreign('oferta_id')->references('id')->on('ofertas')->onDelete('cascade');
            $table->foreign('oferta_programa_id')->references('id')->on('oferta_programa')->onDelete('cascade');
            $table->foreign('programa_id')->references('id')->on('programas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preinscritos');
    }
};
