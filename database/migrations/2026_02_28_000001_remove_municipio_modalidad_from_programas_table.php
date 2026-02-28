<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->dropColumn(['municipio', 'modalidad']);
        });
    }

    public function down(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->string('municipio')->nullable();
            $table->string('modalidad')->nullable();
        });
    }
};
