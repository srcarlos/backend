<?php

use Illuminate\Database\Seeder;

class ConfBodegasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bodegas')->insert([
            [
                'codigo' => '001',
                'nombre' => 'Congelador',
                'ubicacion' => 'ubicacion bodega 001',
                'cocina_id' => 1,
                'tipo' => "A",
            ],
            [
                'codigo' => '002',
                'nombre' => 'Refrigerador 1',
                'ubicacion' => 'ubicacion bodega 002',
                'cocina_id' => 2,
                'tipo' => "A",
            ],
            [
                'codigo' => '003',
                'nombre' => 'Refrigerador 2',
                'ubicacion' => 'ubicacion bodega 003',
                'cocina_id' => 3,
                'tipo' => "B",
            ],
            [
                'codigo' => '004',
                'nombre' => 'Percha 1',
                'ubicacion' => 'ubicacion bodega 004',
                'cocina_id' => 4,
                'tipo' => "C",
            ],
        ]);
    }
}
