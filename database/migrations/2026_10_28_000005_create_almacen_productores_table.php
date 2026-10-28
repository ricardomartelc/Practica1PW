<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('almacen_productores', function (Blueprint $table) {
            $table->foreignId('productor_id')->constrained('productores')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('stock')->default(0);
            $table->decimal('precio_origen', 10, 2);
            $table->primary(['productor_id', 'producto_id']);
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE almacen_productores ADD CONSTRAINT ck_almacen_stock CHECK (stock >= 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('almacen_productores');
    }
};
