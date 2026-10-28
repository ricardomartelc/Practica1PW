<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{
    /**
     * Inicializa la tabla productos ejecutando su fichero SQL.
     */
    public function run(): void
    {
        DB::unprepared(file_get_contents(database_path('seeders/sql/productos.sql')));
    }
}
