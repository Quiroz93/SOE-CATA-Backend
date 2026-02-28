<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('oferta_programa', function (Blueprint $table) {
            $table->foreignId('centro_id')->nullable()->after('instructor_id')->constrained('centros')->cascadeOnDelete();
            $table->string('municipio')->nullable()->after('centro_id');
        });
    }

    public function down(): void
    {
        Schema::table('oferta_programa', function (Blueprint $table) {
            $table->dropForeign(['centro_id']);
            $table->dropColumn(['centro_id', 'municipio']);
        });
    }
};
