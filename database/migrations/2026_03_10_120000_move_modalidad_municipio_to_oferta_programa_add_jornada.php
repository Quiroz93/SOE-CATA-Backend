<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('oferta_programa', function (Blueprint $table) {
            if (!Schema::hasColumn('oferta_programa', 'municipio')) {
                $table->string('municipio')->nullable()->after('modalidad');
            }
            if (!Schema::hasColumn('oferta_programa', 'jornada')) {
                $table->enum('jornada', ['diurna', 'nocturna', 'mixta'])->nullable()->after('municipio');
            }
        });

        if (Schema::hasColumn('programas', 'modalidad')) {
            DB::table('oferta_programa')
                ->join('programas', 'programas.id', '=', 'oferta_programa.programa_id')
                ->whereNull('oferta_programa.modalidad')
                ->update([
                    'oferta_programa.modalidad' => DB::raw('programas.modalidad'),
                ]);
        }

        if (Schema::hasColumn('programas', 'municipio')) {
            DB::table('oferta_programa')
                ->join('programas', 'programas.id', '=', 'oferta_programa.programa_id')
                ->whereNull('oferta_programa.municipio')
                ->update([
                    'oferta_programa.municipio' => DB::raw('programas.municipio'),
                ]);
        }

        // Backfill to avoid nulls before making columns required.
        DB::table('oferta_programa')->whereNull('modalidad')->update(['modalidad' => 'Presencial']);
        DB::table('oferta_programa')->whereNull('municipio')->update(['municipio' => 'Por definir']);
        DB::table('oferta_programa')->whereNull('jornada')->update(['jornada' => 'diurna']);

        Schema::table('programas', function (Blueprint $table) {
            if (Schema::hasColumn('programas', 'modalidad')) {
                $table->dropColumn('modalidad');
            }
            if (Schema::hasColumn('programas', 'municipio')) {
                $table->dropColumn('municipio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            if (!Schema::hasColumn('programas', 'modalidad')) {
                $table->string('modalidad')->nullable();
            }
            if (!Schema::hasColumn('programas', 'municipio')) {
                $table->string('municipio')->nullable();
            }
        });

        Schema::table('oferta_programa', function (Blueprint $table) {
            if (Schema::hasColumn('oferta_programa', 'jornada')) {
                $table->dropColumn('jornada');
            }
            if (Schema::hasColumn('oferta_programa', 'municipio')) {
                $table->dropColumn('municipio');
            }
        });
    }
};
