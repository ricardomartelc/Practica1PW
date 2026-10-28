<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiendaVendedoresSeeder extends Seeder
{
    /**
     * Inicializa la tabla tienda_vendedores ejecutando su fichero SQL.
     */
    public function run(): void
    {
        DB::unprepared(file_get_contents(database_path('seeders/sql/tienda_vendedores.sql')));
    }
}
