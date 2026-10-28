<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tienda_vendedores', function (Blueprint $table) {
            $table->foreignId('vendedor_id')->constrained('vendedores')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('stock')->default(0);
            $table->decimal('precio_b2c', 10, 2);
            $table->decimal('precio_b2b', 10, 2);
            $table->integer('minimo_b2b')->default(1);
            $table->integer('descuento_pct')->default(0);
            $table->primary(['vendedor_id', 'producto_id']);
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE tienda_vendedores ADD CONSTRAINT ck_tienda_stock CHECK (stock >= 0)');
            DB::statement('ALTER TABLE tienda_vendedores ADD CONSTRAINT ck_tienda_descuento CHECK (descuento_pct BETWEEN 0 AND 100)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tienda_vendedores');
    }
};
