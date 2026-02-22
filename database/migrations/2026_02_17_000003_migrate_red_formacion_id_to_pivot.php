<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('programas', 'red_formacion_id')) {
            $programas = DB::table('programas')->select('id', 'red_formacion_id')->whereNotNull('red_formacion_id')->get();
            foreach ($programas as $programa) {
                DB::table('programa_red_formacion')->insert([
                    'programa_id' => $programa->id,
                    'red_formacion_id' => $programa->red_formacion_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // No revertir datos
    }
};
