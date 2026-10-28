<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClientesSeeder::class,
            ProductoresSeeder::class,
            ProductosSeeder::class,
            VendedoresSeeder::class,
            SensoresSeeder::class,
            PedidosSeeder::class,
            DetallesSeeder::class,
        ]);
    }
}