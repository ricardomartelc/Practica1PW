<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nif', 50)->unique();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('nombre', 255);
            $table->string('apellidos', 255)->nullable();
            $table->enum('tipo', ['particular', 'empresa'])->default('particular');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
