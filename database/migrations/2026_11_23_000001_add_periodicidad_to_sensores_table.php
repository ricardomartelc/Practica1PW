<?php

// Práctica 4, Bloque 2 (RMC) — Añade a cada sensor la escala de periodicidad
// de sus muestras: minuto, hora o día. Los sensores existentes quedan en 'hora'.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sensores', function (Blueprint $table) {
            $table->enum('periodicidad', ['minuto', 'hora', 'dia'])->default('hora')->after('umbral_max');
        });
    }

    public function down(): void
    {
        Schema::table('sensores', function (Blueprint $table) {
            $table->dropColumn('periodicidad');
        });
    }
};
