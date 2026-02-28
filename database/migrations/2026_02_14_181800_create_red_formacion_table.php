<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('red_formacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        // Eliminar primero la relación en programas si existe
        if (Schema::hasColumn('programas', 'red_formacion_id')) {
            Schema::table('programas', function (Blueprint $table) {
                $table->dropForeign(['red_formacion_id']);
                $table->dropColumn('red_formacion_id');
            });
        }
        Schema::dropIfExists('red_formacion');
    }
};
