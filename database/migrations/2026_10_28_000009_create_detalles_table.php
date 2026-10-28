<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnUpdate();
            $table->integer('cantidad');
            $table->decimal('precio_aplicado', 10, 2);
            $table->unique(['pedido_id', 'producto_id'], 'uq_detalles_pedido_producto');
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE detalles ADD CONSTRAINT ck_detalles_cantidad CHECK (cantidad > 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles');
    }
};
