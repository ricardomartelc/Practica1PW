<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('categoria', 100);
            $table->string('unidad', 50);
            $table->decimal('co2_por_unidad', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
