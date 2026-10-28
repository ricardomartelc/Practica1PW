<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoresSeeder extends Seeder
{
    /**
     * Inicializa la tabla productores ejecutando su fichero SQL.
     */
    public function run(): void
    {
        DB::unprepared(file_get_contents(database_path('seeders/sql/productores.sql')));
    }
}
