<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove duplicados existentes (mantem o registro de menor id por dia/tipo/planta/usuario).
        $duplicados = DB::table('care_logs')
            ->select(DB::raw('MIN(id) as keep_id'))
            ->groupBy('user_id', 'plant_id', 'tipo', 'data')
            ->pluck('keep_id');

        if ($duplicados->isNotEmpty()) {
            DB::table('care_logs')->whereNotIn('id', $duplicados)->delete();
        }

        Schema::table('care_logs', function (Blueprint $table) {
            // Garante: 1 cuidado do mesmo tipo, por planta, por usuario, por dia.
            $table->unique(['user_id', 'plant_id', 'tipo', 'data'], 'care_logs_unico_por_dia');
        });
    }

    public function down(): void
    {
        Schema::table('care_logs', function (Blueprint $table) {
            $table->dropUnique('care_logs_unico_por_dia');
        });
    }
};
