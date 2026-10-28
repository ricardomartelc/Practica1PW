<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->id();
            $table->string('nif', 50)->unique();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('nombre_puesto', 255);
            $table->string('ubicacion', 255);
            $table->enum('entrega', ['recogida', 'envio'])->default('recogida');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendedores');
    }
};
