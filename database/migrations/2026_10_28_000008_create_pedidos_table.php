<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnUpdate();
            $table->foreignId('vendedor_id')->constrained('vendedores')->cascadeOnUpdate();
            $table->dateTime('fecha')->useCurrent();
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('estado', ['pendiente', 'listo_recoger', 'recogido', 'enviado', 'recibido'])
                  ->default('pendiente');
            $table->string('direccion_entrega', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
