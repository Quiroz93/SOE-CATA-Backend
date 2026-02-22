<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('programas', 'red_formacion_id')) {
            Schema::table('programas', function (Blueprint $table) {
                $table->dropForeign(['red_formacion_id']);
                $table->dropColumn('red_formacion_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->unsignedBigInteger('red_formacion_id')->nullable();
            $table->foreign('red_formacion_id')->references('id')->on('red_formacion')->onDelete('set null');
        });
    }
};
