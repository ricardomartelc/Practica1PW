<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('vendedores')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('codigo_dispositivo', 100)->unique();
            $table->enum('tipo', ['temperatura', 'humedad'])->default('temperatura');
            $table->decimal('valor_actual', 10, 2)->nullable();
            $table->decimal('umbral_min', 10, 2);
            $table->decimal('umbral_max', 10, 2);
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE sensores ADD CONSTRAINT ck_sensores_umbrales CHECK (umbral_min < umbral_max)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sensores');
    }
};
